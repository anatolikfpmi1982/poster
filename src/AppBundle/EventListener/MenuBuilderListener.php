<?php

namespace AppBundle\EventListener;

use Sonata\AdminBundle\Event\ConfigureMenuEvent;

class MenuBuilderListener
{
    public function addMenuItems(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('Система', array())
            ->setExtras([
                'icon' => '<i class="fa fa-cogs"></i>',
            ])
            ->addChild('settings', [
                'label' => 'Настройки сайта',
                'route' => 'sonata_admin_settings',
            ])
            ->getParent()
            ->addChild('frame-settings', [
                'label' => 'Настройки рамы',
                'route' => 'sonata_admin_frame_settings',
            ])
            ->getParent()
            ->addChild('help-settings', [
                'label' => 'Настройки подсказок',
                'route' => 'sonata_admin_help_settings',
            ]);
    }
}