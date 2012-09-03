<?php
// src/Acme/DemoBundle/Menu/Builder.php
namespace Smirik\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Smirik\AdminBundle\Event\ConfigureMenuEvent;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');
//        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        $menu->addChild('admin.home', array('route' => 'admin_main'));

        $this->container->get('event_dispatcher')->dispatch(ConfigureMenuEvent::CONFIGURE, new ConfigureMenuEvent($factory, $menu, $this->container));
        return $menu;
    }
}