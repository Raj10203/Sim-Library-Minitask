<?php

namespace App\EventListener;

use App\Event\LoanReturnedEvent;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;

class LoanReturnedEventListener
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[NoReturn]
    #[AsEventListener(event: LoanReturnedEvent::LOAN_RETURNED)]
    public function onLoanReturned(LoanReturnedEvent $event): void
    {
        $loan = $event->getLoan();
        $book = $loan->getBook();

        // 1. Mark the book as available
        $book->setIsAvailable(true);

        // 2. Simulate email log
        $user = $loan->getUser();
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
        $this->messageBus->dispatch(new SendEmailMessage($email));
    }
}