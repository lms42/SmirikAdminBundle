<?php

namespace Smirik\AdminBundle\Model;

use FOS\UserBundle\Propel\User as BaseUser;

class User extends BaseUser
{
	
	public function __toString()
	{
		return $this->getUsername();
	}
	
}