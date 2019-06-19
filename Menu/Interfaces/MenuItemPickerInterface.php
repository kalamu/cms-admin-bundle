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

/**
 * Interface for MenuItemPicker service
 */
interface MenuItemPickerInterface
{

    /**
     * Get the list of items
     *
     * @param type $page
     * @param type $limit
     * @param type $context
     * @param type $search
     */
    public function getItems($page, $limit = 10, $context = null, $search = '');

    /**
     * Get on specific item
     *
     * @param type $id
     * @param type $context
     */
    public function getItem($id, $context = null);

    /**
     * Get the associated content manager
     */
    public function getManager();

}