<?php

namespace Smirik\AdminBundle\Event;

use Smirik\AdminBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param \Smirik\AdminBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();
        $menu->addChild('admin.users.menu');
        $menu['admin.users.menu']->addChild('admin.users.menu', array('route' => 'admin_users_index'));
        $menu['admin.users.menu']->addChild('admin.groups.menu', array('route' => 'admin_groups_index'));
    }
    
}