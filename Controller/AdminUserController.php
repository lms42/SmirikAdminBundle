<?php

namespace Smirik\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Smirik\AdminBundle\Controller\Base\AdminUserController as BaseController;

class AdminUserController extends BaseController
{
	/**
	 * @Route("/admin/users/{id}/stat", name="admin_users_stat")
	 * @Template("SmirikAdminBundle:Admin/User:stat.html.twig")
	 * @Secure(roles="ROLE_ADMIN")
	 */
	public function statAction($id)
	{
	    $um = $this->get('fos_user.user_manager');
	    $user = $um->findUserBy(array('Id' => $id));

		$cm   = $this->get('course.manager');
		
		$response = $cm->getResults($user);
		return $response;
	}

}

