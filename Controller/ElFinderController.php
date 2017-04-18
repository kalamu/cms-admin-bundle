<?php

namespace Kalamu\CmsAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller pour afficher le sélecteur Elfinder dans TinyMCE
 */
class ElFinderController extends Controller {

    /**
     * Ouvre une fenêtre elfinder
     */
    public function showAction(){
        $efParameters = $this->container->getParameter('fm_elfinder');
        $assetsPath   = $efParameters['assets_path'];
        return $this->render('KalamuCmsAdminBundle:ElFinder:show.html.twig', array('prefix' => $assetsPath));
    }

}
