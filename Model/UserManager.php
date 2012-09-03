<?php

namespace Smirik\AdminBundle\Model;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Symfony\Component\Security\Core\User\UserInterface;

class UserManager implements UserProviderInterface
{
	
	public function loadUserByUsername($username)
	{
		$profile = ProfileQuery::create()->findOneByVkId($username);
		if ($profile)
		{
			$user = UserQuery::create()->findPk($profile->getUserId());
			return $user;
		}
		throw new UsernameNotFoundException('Not found');
	}

	public function createUserFromUid($uid)
	{
		$user = new User();
		$user->setUsername($uid);
		$user->setEmail($uid);
		$user->save();

		$profile = new Profile();
		$profile->setUser($user);
		$profile->setVkId($uid);
		$profile->save();
		
		return $user;
	}

  public function refreshUser(UserInterface $user)
	{
		$user = UserQuery::create()->findPk($user->getId());
		return $user;
	}
	
	public function supportsClass($class)
	{
		return true;
	}
	
}