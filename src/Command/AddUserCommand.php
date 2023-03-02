<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Stopwatch\Stopwatch;

#[AsCommand(
    name: 'app:add-user',
    description: 'Create user',
)]
class AddUserCommand extends Command
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher
    )
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

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
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

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

        try {
            $user = $this->createUser($email, $password, (bool)$isAdmin);
        }catch (\Exception $exception) {
            $io->error($exception->getMessage());
            return Command::FAILURE;
        }


        $successMessage = sprintf(
            '%s was success created: %s',
            $isAdmin ? 'Admin user' : 'User',
            $email
        );
        $io->success($successMessage);

        $event = $stopwatch->stop('add-user-command');
        $stopwatchMessage = sprintf(
            'New user\'s id: %s / Elapsed time: %.2f ms / Consumed memory: %.2f MB',
            $user->getId(),
            $event->getDuration(),
            $event->getMemory() / 1000 / 1000
        );

        $io->comment($stopwatchMessage);
        
        return Command::SUCCESS;
    }

    private function createUser(string $email, string $password, bool $isAdmin): User
    {
        if ($this->userRepository->findOneBy(['email' => $email])) {
            throw new \RuntimeException('User already exists');
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles([$isAdmin ? 'ROLE_ADMIN' : 'ROLE_USER']);

        $encodedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($encodedPassword);
        $user->setIsVerified(true);

        $this->userRepository->save($user, true);

        return $user;
    }
}
