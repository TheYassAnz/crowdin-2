<?php

namespace App\Service;

use App\Entity\Projects;
use App\Entity\Sources;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;

class NotificationService
{
    public function __construct(
        private MailerInterface $mailer
    ) {}

    public function sendProjectCreationNotification(Projects $project): void
    {
        try {
            $email = (new TemplatedEmail())
                ->from(new Address('noreply@polyglothub.com', 'PolyglotHub'))
                ->to($project->getUser()->getEmail())
                ->subject('New Project Created')
                ->htmlTemplate('emails/project_created.html.twig')
                ->context([
                    'project' => $project,
                    'user' => $project->getUser()
                ]);

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Could not send email: ' . $e->getMessage());
        }
    }

    public function sendSourceCreationNotification(Sources $source): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address('noreply@your-domain.com', 'Crowdin Notification'))
            ->to($source->getProject()->getUser()->getEmail())
            ->subject('New Source Added')
            ->htmlTemplate('emails/source_created.html.twig')
            ->context([
                'source' => $source,
                'project' => $source->getProject(),
                'user' => $source->getProject()->getUser()
            ]);

        $this->mailer->send($email);
    }
}