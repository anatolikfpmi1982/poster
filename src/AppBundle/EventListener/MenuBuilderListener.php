<?php

namespace AppBundle\EventListener;

use Sonata\AdminBundle\Event\ConfigureMenuEvent;

class MenuBuilderListener
{
    public function addMenuItems(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $child = $menu->addChild('settings', [
            'label' => 'Настройки сайта',
            'route' => 'sonata_admin_settings',
        ])->setExtras([
            'icon' => '<i class="fa fa-cogs"></i>',
        ]);
    }
}