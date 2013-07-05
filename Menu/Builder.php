<?php
// src/Acme/DemoBundle/Menu/Builder.php
namespace Smirik\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Smirik\AdminBundle\Event\ConfigureMenuEvent;
use Smirik\AdminBundle\Event\ConfigureMainMenuEvent;

class Builder extends ContainerAware
{
    public function admin(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');
//        $menu->setCurrentUri($this->container->get('request')->getRequestUri());
        $menu->addChild('admin.home', array('route' => 'admin_main'));
        $this->container->get('event_dispatcher')->dispatch(ConfigureMenuEvent::CONFIGURE, new ConfigureMenuEvent($factory, $menu, $this->container));
        return $menu;
    }
    
    public function main(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');
        $this->container->get('event_dispatcher')->dispatch(ConfigureMainMenuEvent::CONFIGURE, new ConfigureMenuEvent($factory, $menu, $this->container));
        return $menu;
    }

    public function getCurrentMenuItem($menu)
    {
        foreach ($menu as $item) {
            if ($item->isCurrent()) {
                return $item;
            }

            if ($item->getChildren() && $current_child = $this->getCurrentMenuItem($item)) {
                return $current_child;
            }
        }

        return null;
    }
}