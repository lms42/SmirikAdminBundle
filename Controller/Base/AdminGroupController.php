<?php

namespace Smirik\AdminBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

class AdminGroupController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'groups';
	public $bundle = 'SmirikAdminBundle';

	public function getQuery()
	{
		return \FOS\UserBundle\Propel\GroupQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\AdminBundle\Form\Type\GroupType;
	}
	
	public function getObject()
	{
		return new \FOS\UserBundle\Propel\Group;
	}

}

