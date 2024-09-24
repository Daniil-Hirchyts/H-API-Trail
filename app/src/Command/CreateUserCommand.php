<?php
// src/Command/CreateUserCommand.php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserManager $userManager,
        private readonly UserRepository $userRepository
    )
    {
        parent::__construct();
    }

    public static function getDefaultName(): string
    {
        return 'app:create-user';
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new user.')
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the user.')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the user.')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password of the user.')
            ->addArgument('visible', InputArgument::OPTIONAL, 'The visibility of the user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $username = $input->getArgument('username');
        if (!$username) {
            $question = new Question('Please enter the username: ');
            $username = $helper->ask($input, $output, $question);
        }

        $email = $input->getArgument('email');
        if (!$email) {
            $question = new Question('Please enter the email: ');
            $email = $helper->ask($input, $output, $question);
        }

        $password = $input->getArgument('password');
        if (!$password) {
            $question = new Question('Please enter the password: ');
            $password = $helper->ask($input, $output, $question);
        }

        $visible = $input->getArgument('visible');
        if (!$visible) {
            $question = new Question('Is the user visible (true/false)? ', 'true');
            $visible = $helper->ask($input, $output, $question);
        }

        // Validate arguments
        if (!is_string($username) || !is_string($email) || !is_string($password) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $output->writeln('Invalid input types for username, email, or password.');
            return Command::FAILURE;
        }

        if (!in_array($visible, ['true', 'false'], true)) {
            $output->writeln('Invalid input type for visible. It should be "true" or "false".');
            return Command::FAILURE;
        }

        $visible = $visible === 'true';

        $user = $this->userRepository->getUserByUsernameOrEmail($username, $email);

        if (!empty($user)) {
            $output->writeln($user);
            $output->writeln('User already exists!');
            return Command::FAILURE;
        } else {
            $output->writeln('Creating user...');
        }

        $user = new User();

        $this->userManager->processNewUser($user, $password);

        $user->setVisible($visible);
        $user->setLastLogin(new \DateTime());
        $user->setCreatedAt(new \DateTime());
        $user->setUpdatedAt(new \DateTime());
        $user->setUsername($username);
        $user->setEmail($email);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('User successfully created!');

        return Command::SUCCESS;
    }
}