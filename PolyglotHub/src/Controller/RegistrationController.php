<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
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
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $logger->debug('Entering registration process.');
        $user = new User();
        
        // Set user as unverified
        $user->setVerified(false);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $logger->debug('Form submitted.');
            
            if ($form->isValid()) {
                $logger->debug('Form is valid. Processing registration.');

                $plainPassword = $form->get('Password')->getData();
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
                $selectedRoles = $form->get('roles')->getData();
                $roles = array_merge($selectedRoles, ['ROLE_USER']);
                $user->setRoles($roles);
                $user->setCreateDate(new \DateTime());
                $user->setVerificationToken($this->tokenGenerator->generateToken());

                try {
                    $entityManager->persist($user);
                    $entityManager->flush();
                    $logger->debug('User registered successfully.');

                    // Send confirmation email
                    $this->sendConfirmationEmail($user);

                    return $this->redirectToRoute('app_login');

                } catch (\Exception $e) {
                    $logger->error('Error during registration: ' . $e->getMessage());
                    $this->addFlash('error', 'An error occurred during registration.');
                }
            } else {
                $logger->warning('Form is invalid. Errors: ' . json_encode($form->getErrors()));
            }
        }

        return $this->render('test.html.twig', [
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

    #[Route('/verify/email/{token}', name: 'app_verify_email')]
    public function verifyUserEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['verificationToken' => $token]);

        if (!$user) {
            $this->addFlash('verify_email_error', 'Invalid verification token.');
            return $this->redirectToRoute('app_register');
        }

        
        $user->setIsVerified(true);
        $user->setVerificationToken(null); // Clear the token after verification
        $entityManager->flush();

        $this->addFlash('success', 'Your email address has been verified.');
        return $this->redirectToRoute('app_login');
    }
}