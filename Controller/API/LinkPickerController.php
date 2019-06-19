<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * API for link selection from the WYSIWYG editor
 */
class LinkPickerController extends Controller
{

    /**
     * Full link picker with capabilities to edit displayed text, class, ...
     * @return type
     */
    public function indexAction(){

        return $this->render('KalamuCmsAdminBundle:LinkPicker:index.html.twig', array(
            'items_pickers_manager' => $this->get('kalamu_cms_admin.menu_item.manager'),
        ));
    }

    /**
     * Minimal link picker without configuration option
     */
    public function baseAction(){

        return $this->render('KalamuCmsAdminBundle:LinkPicker:base.html.twig', array(
            'items_pickers_manager' => $this->get('kalamu_cms_admin.menu_item.manager'),
        ));
    }

}