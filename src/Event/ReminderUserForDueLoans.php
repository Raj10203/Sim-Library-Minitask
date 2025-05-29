<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ReminderUserForDueLoans extends Event
{
    public const REMINDERUSERFORDUELOANS = 'loan.reminder_users_for_due_loans';
}