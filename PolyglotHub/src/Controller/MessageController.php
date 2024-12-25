<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/messages')]
class MessageController extends AbstractController
{
    #[Route('/', name: 'app_messages')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('message/index.html.twig', [
            'receivedMessages' => $entityManager->getRepository(Message::class)->findBy(['recipient' => $this->getUser()]),
            'sentMessages' => $entityManager->getRepository(Message::class)->findBy(['sender' => $this->getUser()]),
            'users' => $entityManager->getRepository(User::class)->findAll()
        ]);
    }

    #[Route('/new/{recipientId?}', name: 'app_messages_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, ?int $recipientId = null): Response
    {
        $message = new Message();
        $message->setSender($this->getUser());

        if ($recipientId) {
            $recipient = $entityManager->getRepository(User::class)->find($recipientId);
            if ($recipient) {
                $message->setRecipient($recipient);
            }
        }

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Message sent successfully!');
            return $this->redirectToRoute('app_messages');
        }

        return $this->render('message/new.html.twig', [
            'form' => $form->createView(),
            'recipient' => $message->getRecipient()
        ]);
    }

    #[Route('/{id}/mark-read', name: 'app_messages_mark_read')]
    public function markAsRead(Message $message, EntityManagerInterface $entityManager): Response
    {
        if ($message->getRecipient() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $message->setIsRead(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_messages');
    }
}