<?php

namespace Kalamu\CmsAdminBundle\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller pour sélectionner des liens depuis le Wysiwyg
 */
class LinkPickerController extends Controller
{

    /**
     * Linkpicker complet avec édition du text, des class, etc...
     * Utilisé pour faire des liens dans le WYDIWYG
     * @return type
     */
    public function indexAction(){

        return $this->render('KalamuCmsAdminBundle:LinkPicker:index.html.twig', array(
            'items_pickers_manager' => $this->get('kalamu_cms_admin.menu_item.manager'),
        ));
    }

    /**
     * Picker minimalise pour récupérer uniquement l'adresse d'un contenu
     */
    public function baseAction(){

        return $this->render('KalamuCmsAdminBundle:LinkPicker:base.html.twig', array(
            'items_pickers_manager' => $this->get('kalamu_cms_admin.menu_item.manager'),
        ));
    }

}