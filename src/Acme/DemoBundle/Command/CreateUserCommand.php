<?php

namespace Acme\DemoBundle\Command;

use Acme\DemoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CreateUserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('acme:user:create')
            ->setDescription('Creates a new user')
            ->addArgument('username',null, InputArgument::REQUIRED, 'Specify username')
            ->addArgument('password',null, InputArgument::REQUIRED, 'Specify password')
            ->setHelp(
                <<<EOT
                    The <info>%command.name%</info>command creates a new user.

<info>php %command.full_name% username password</info>

EOT
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userManager = $this->getContainer()->get('platform.user.manager');
        $encoderService =  $encoderService = $this->getContainer()->get('security.encoder_factory');

        $user = new User();

        $user->setUsername($input->getArgument('username'));

        $user->setSalt(md5(uniqid()));

        $encoder = $encoderService->getEncoder($user);
        $user->setPassword($encoder->encodePassword($input->getArgument('password'), $user->getSalt()));

        $userManager->persist($user);
        $userManager->flush();
        $output->writeln(
            sprintf(
                'Added a new user with public id <info>%s</info>, username <info>%s</info>',
                $user->getId(),
                $user->getUsername()
            )
        );
    }
}