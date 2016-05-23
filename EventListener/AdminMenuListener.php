<?php

/**
 * This file is part of the "NFQ Bundles" package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nfq\BannerBundle\EventListener;

use Knp\Menu\ItemInterface;
use Nfq\AdminBundle\Event\ConfigureMenuEvent;
use Nfq\AdminBundle\Menu\AdminMenuListener as AdminMenuListenerBase;

/**
 * Class AdminMenuListener
 * @package Nfq\BannerBundle\EventListener
 */
class AdminMenuListener extends AdminMenuListenerBase
{
    /**
     * {@inheritdoc}
     */
    protected function doMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();
        $node = $this->getBannerNode();

        $menu->addChild($node);
    }

    /**
     * @return ItemInterface
     */
    private function getBannerNode()
    {
        return $this
            ->factory
            ->createItem('admin.side_menu.banner', ['route' => 'nfq_banner_list'])
            ->setExtras(
                [
                    'orderNumber' => 13,
                    'translation_domain' => 'adminInterface',
                ]
            );
    }
}
