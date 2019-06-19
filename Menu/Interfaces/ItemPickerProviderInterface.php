<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Menu\Interfaces;

use Kalamu\CmsAdminBundle\Manager\MenuItemPickerManager;

/**
 * Interface for MenuItemPicker providers
 */
interface ItemPickerProviderInterface
{

    /**
     * This method is called to register ItemPickers 
     *
     * @param MenuItemPickerManager $manager
     */
    public function registerMenuItemPickers(MenuItemPickerManager $manager);

}