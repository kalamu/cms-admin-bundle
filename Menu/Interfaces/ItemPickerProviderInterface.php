<?php

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