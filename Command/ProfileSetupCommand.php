<?php

namespace Smirik\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use FOS\UserBundle\Propel\UserQuery;
use Smirik\AdminBundle\Model\Profile;

class ProfileSetupCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('admin:profile:setup')
            ->setDescription('Setup profiles for users')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $users = UserQuery::create()
            ->joinProfile('Profile', 'left join')
            ->useProfileQuery('p', 'left join')
                ->filterByUserId(null)
            ->endUse()
            ->find();
        
        foreach ($users as $user)
        {
            $profile = new Profile();
            $profile->setUser($user);
            $profile->save();
        }
        $output->writeln('<info>Success!</info>');
    }
}
