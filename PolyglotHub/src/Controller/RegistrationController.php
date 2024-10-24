<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class RegistrationController extends AbstractController
{
    private MailerInterface $mailer;
    private TokenGeneratorInterface $tokenGenerator;

    public function __construct(MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator)
    {
        $this->mailer = $mailer;
        $this->tokenGenerator = $tokenGenerator;
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher, 
        EntityManagerInterface $entityManager, 
        LoggerInterface $logger
    ): Response
    {
        $logger->debug('Entering registration process.');
        $user = new User();
        
        // Set user as unverified initially
        $user->setVerified(false);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logger->debug('Form is valid. Processing registration.');

            $plainPassword = $form->get('plainPassword')->getData();  // Ensure 'plainPassword' is correct field
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Set user roles, default to ROLE_USER
            $selectedRoles = $form->get('roles')->getData();
            $roles = array_merge($selectedRoles, ['ROLE_USER']);
            $user->setRoles($roles);

            $user->setCreateDate(new \DateTime());
            $user->setVerificationToken($this->tokenGenerator->generateToken());  // Generate verification token

            try {
                $entityManager->persist($user);
                $entityManager->flush();
                $logger->debug('User registered successfully.');

            
                $this->sendConfirmationEmail($user);

                $this->addFlash('success', 'Registration successful! Please check your email to verify your account.');

                return $this->redirectToRoute('app_login');

            } catch (\Exception $e) {
                $logger->error('Error during registration: ' . $e->getMessage());
                $this->addFlash('error', 'An error occurred during registration.');
            }
        } elseif ($form->isSubmitted()) {
            $logger->warning('Form is invalid. Errors: ' . json_encode($form->getErrors(true)));
        }

        return $this->render('register/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    private function sendConfirmationEmail(User $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('a.aliev@initum.fr', 'Mail Verification Bot'))
            ->to($user->getEmail())
            ->subject('Please Confirm Your Email')
            ->htmlTemplate('verify.html.twig')
            ->context([
                'user' => $user,
                'token' => $user->getVerificationToken()
            ]);

        $this->mailer->send($email);
    }

    #[Route('/verify-email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->findOneBy(['verificationToken' => $token]);

        if (!$user) {
            $this->addFlash('danger', 'The verification token is invalid or expired.');
            return $this->render('registration/verification_failed.html.twig');
        }

        $user->setVerified(true);
        $user->setVerificationToken(null);

        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Your email has been verified. You can now log in.');

        return $this->redirectToRoute('app_login');
    }

}