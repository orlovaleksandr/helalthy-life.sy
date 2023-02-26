<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add-user',
    description: 'Create user',
)]
class AddUserCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addOption('email', 'email', InputArgument::REQUIRED, 'Email')
            ->addOption('password', 'password', InputArgument::REQUIRED, 'Password')
            ->addOption('admin', 'a', InputArgument::OPTIONAL, 'Is user an admin', false)

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Add user');

        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $isAdmin = $input->getOption('admin');


        if (!$email) {
            $email = $io->ask('Type your Email');
        }

        if (!$password) {
            $password = $io->askHidden('Type your Password');
        }

        if (!$isAdmin) {
            $isAdmin = $io->askQuestion(new Question('Are you an admin?', 0));
        }


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
