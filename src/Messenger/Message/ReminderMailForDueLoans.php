<?php

namespace App\Messenger\Message;

use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;

class ReminderMailForDueLoans
{
    public function __construct(private User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}