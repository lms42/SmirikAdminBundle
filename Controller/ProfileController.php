<?php

namespace Smirik\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use JMS\SecurityExtraBundle\Annotation\Secure;
use Smirik\AdminBundle\Form\Type\ProfileType;

/**
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
      * @Route("/", name="profile_index")
      * @Template("SmirikAdminBundle:Profile:index.html.twig", vars={"get"})
      * @Secure(roles="ROLE_USER")
      */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * @Route("/edit", name="profile_edit")
     * @Template()
     * @Secure(roles="ROLE_USER")
     */
    public function editAction()
    {
        $profile = $this->getUser()->getProfile();
        $form    = $this->createForm(new ProfileType(), $profile);
        $request = $this->getRequest();
        if ('POST' === $request->getMethod())
        {
            $form->submit($request);
            if ($form->isValid())
            {
                if ($this->getUser()->getId() != $profile->getUserId())
                {
                    return $this->redirect($this->generateUrl('profile_index'));
                }
                $profile->save();
                return $this->redirect($this->generateUrl('profile_index'));
            }
        }
        
        return array(
            'profile' => $profile,
            'form'    => $form->createView(),
        );
    }
    
}
