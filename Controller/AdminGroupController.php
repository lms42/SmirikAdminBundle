<?php

namespace Smirik\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

use Smirik\PropelAdminBundle\Column\Column;
use Smirik\PropelAdminBundle\Column\CollectionColumn;
use Smirik\PropelAdminBundle\Action\Action;
use Smirik\PropelAdminBundle\Action\ObjectAction;
use Smirik\PropelAdminBundle\Action\SingleAction;

class AdminGroupController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'groups';

	public function setup()
	{
		$this->configure(array(
								     array('name' => 'id', 'label' => 'Id', 'type' => 'integer', 'options' => array(
												 'editable' => false,
												 'listable' => true,
												 'sortable' => true,
												 'filterable' => true)),
		                 array('name' => 'name', 'label' => 'Name', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true))
		                 ),
		                 array('new' => new SingleAction('New', 'new', 'admin_groups_new', true),
											'edit' => new ObjectAction('Edit', 'edit', 'admin_groups_edit', true),
											'delete' => new ObjectAction('Delete', 'delete', 'admin_groups_delete', true, true))
		                );
	}

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

