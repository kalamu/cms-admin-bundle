<?php

namespace Kalamu\CmsAdminBundle\Menu\Interfaces;

/**
 * Interface des service de MenuItemPicker
 */
interface MenuItemPickerInterface
{

    /**
     * Retourne une liste d'item pour le picker
     * @param type $page
     * @param type $limit
     * @param type $context
     * @param type $search
     */
    public function getItems($page, $limit = 10, $context = null, $search = '');

    /**
     * Retourne les détails d'un item
     * @param type $id
     * @param type $context
     */
    public function getItem($id, $context = null);

    /**
     * Retourne le manager de contenu utilisé
     */
    public function getManager();

}