<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Psr\Log\LoggerInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
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

            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setCreateDate(new \DateTime());

            try {
                $entityManager->persist($user);
                $entityManager->flush();
                $logger->debug('User registered successfully.');

                // Send confirmation email
                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('a.aliev@initum', 'Mail verification bot'))
                        ->to((string) $user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('verify.html.twig')
                );

                // Remove automatic login
                // $security->login($user, 'form_login', 'main');

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

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
            $this->addFlash('success', 'Your email address has been verified.');
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());
            return $this->redirectToRoute('app_register');
        }

        return $this->redirectToRoute('app_login');
    }
}
