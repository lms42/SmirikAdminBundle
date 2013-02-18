<?php

namespace Smirik\AdminBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

class AdminUserController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'users';
	public $bundle = 'SmirikAdminBundle';

	public function getQuery()
	{
		return \FOS\UserBundle\Propel\UserQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\AdminBundle\Form\Type\UserType;
	}
	
	public function getObject()
	{
		return new \FOS\UserBundle\Propel\User;
	}

}

