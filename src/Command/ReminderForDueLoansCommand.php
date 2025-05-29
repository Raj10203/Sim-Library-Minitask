<?php

namespace App\Command;

use App\Messenger\Message\ReminderMailForDueLoans;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'reminderForDueLoans',
    description: 'This command sends an email to users who have at least 2 loans that are overdue by at least 25 days.',
)]
class ReminderForDueLoansCommand extends Command
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private UserRepository      $userRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $users = $this->userRepository->findUserByDueLoans();

        foreach ($users as $user) {
            $this->commandBus->dispatch(new ReminderMailForDueLoans($user));
        }

        $io->success('We have sent mail to applicable users.');

        return Command::SUCCESS;
    }
}
