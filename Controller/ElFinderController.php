<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller to show elfinder selector in TinyMCE
 */
class ElFinderController extends Controller {

    /**
     * Open an elfinder window
     */
    public function showAction(){
        $efParameters = $this->container->getParameter('fm_elfinder');
        $assetsPath   = $efParameters['assets_path'];
        return $this->render('KalamuCmsAdminBundle:ElFinder:show.html.twig', array('prefix' => $assetsPath));
    }

}
