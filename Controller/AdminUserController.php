<?php

namespace Smirik\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Smirik\AdminBundle\Controller\Base\AdminUserController as BaseController;

use Smirik\AdminBundle\Model\Profile;

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
    
    public function newAction()
    {
        $this->initialize();
        $this->object = $this->getObject();

        $request = $this->getRequest();
        $form    = $this->createForm($this->getForm(), $this->object);

        if ('POST' == $request->getMethod()) {
            $form->submit($request);
            if ($form->isValid()) {
                $this->get('fos_user.user_manager')->updateUser($this->object);
                $this->object->save();
                
                $profile = new Profile();
                $profile->setUser($this->object);
                $profile->save();

                return $this->redirect($this->generateUrl($this->routes['edit'], array('id' => $this->object->getId())));
            }
        }

        $render = array(
            'layout'  => $this->layout,
            'object'  => $this->object,
            'form'    => $form->createView(),
            'columns' => $this->grid->getColumns(),
            'routes'  => $this->routes,
        );

        return $this->render($this->grid->template('form.new'), $render);
    }
    
    public function editAction($id)
    {
        $this->initialize();
        
        $this->object = $this->getQuery()->findPk($id);
        if (!$this->object) {
            throw $this->createNotFoundException('Not found');
        }

        $request = $this->getRequest();

        $form = $this->createForm($this->getForm(), $this->object);

        if ('POST' == $request->getMethod()) {
            $form->submit($request);
            if ($form->isValid()) {
                $this->get('fos_user.user_manager')->updateUser($this->object);
                $this->object->save();

                return $this->redirect($this->generateUrl($this->routes['index']));
            }
        }

        $render = array(
            'layout'  => $this->layout,
            'object'  => $this->object,
            'form'    => $form->createView(),
            'columns' => $this->grid->getColumns(),
            'routes'  => $this->routes,
        );

        return $this->render($this->grid->template('form.edit'), $render);
    }

}

