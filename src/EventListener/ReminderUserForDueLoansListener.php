<?php

namespace App\EventListener;

use App\Event\ReminderUserForDueLoans;
use App\Repository\UserRepository;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ReminderUserForDueLoansListener
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UserRepository $userRepository,
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[NoReturn]
    #[AsEventListener(event: ReminderUserForDueLoans::REMINDERUSERFORDUELOANS)]
    public function onLoanReturned(ReminderUserForDueLoans $event): void
    {
        $users = $this->userRepository->findUserByDueLoans();

        foreach ($users as $user) {

        }

        $email = (new Email())
            ->from('admin@example.com')
            ->to($user->getEmail())
            ->subject('Book Returned Confirmation')
            ->text(sprintf(
                "Hi %s,\n\nThank you for returning '%s'.\n\nRegards,\nLibrary Team",
                $user->getFirstName(),
                $book->getTitle()
            ));

        // Send email
        $this->mailer->send($email);
    }
}