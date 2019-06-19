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
 * Interface des providers de MenuItemPicker
 */
interface ItemPickerProviderInterface
{

    /**
     * Méthode appelée pour enregistrer les pickers
     * @param MenuItemPickerManager $manager
     */
    public function registerMenuItemPickers(MenuItemPickerManager $manager);

}