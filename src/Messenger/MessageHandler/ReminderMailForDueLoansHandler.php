<?php

namespace App\Messenger\MessageHandler;

use App\Entity\Loan;
use App\Messenger\Message\ReminderMailForDueLoans;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;

#[AsMessageHandler]
class ReminderMailForDueLoansHandler
{
    public function __construct(private readonly MailerInterface $mailer)
    {
    }

    public function __invoke(ReminderMailForDueLoans $message): void
    {
        $user = $message->getUser();
        $email = (new TemplatedEmail())
            ->from(new Address('admin@example.com', 'Admin'))
            ->to(new Address($user->getEmail(), $user->getFirstName() . ' ' . $user->getLastName()))
            ->subject('Book Returned Confirmation')
            ->htmlTemplate('email/warning.html.twig')
            ->context([
                'user' => $user,
            ]);
        $this->mailer->send($email);
    }
}