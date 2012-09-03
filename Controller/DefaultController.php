<?php

namespace Smirik\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
  /**
   * @Route("/admin", name="admin_main")
   * @Template("SmirikAdminBundle::index.html.twig", vars={"get"})
   */
  public function indexAction()
  {
    return array();
  }
}
