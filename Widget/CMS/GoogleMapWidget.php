<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Widget\CMS;

use Kalamu\CmsAdminBundle\Form\Type\GoogleMapMarkerType;
use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Form;

/**
 * Widget to display a google map with optional markers
 */
class GoogleMapWidget extends AbstractConfigurableElement
{

    protected $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function getTitle() {
        return 'cms.google_map.title';
    }

    public function getDescription() {
        return 'cms.google_map.description';
    }

    public function getForm(Form $form){
        $form->add("center_lat", HiddenType::class, array());
        $form->add("center_lon", HiddenType::class, array());
        $form->add("zoom", HiddenType::class, array());

        $form->add('markers', CollectionType::class, array(
            'type'          => GoogleMapMarkerType::class,
            'allow_add'     => true,
            'allow_delete'  => true,
            'delete_empty'  => true
        ));


        return $form;
    }

    /**
     * customize the admin form
     * @param TwigEngine $templating
     * @param type $form
     */
    public function renderConfigForm(TwigEngine $templating, Form $form){
       return $templating->render('KalamuCmsAdminBundle:Form/Widget:google_map.html.twig', array('form' => $form->createView(), 'widget' => $this));
    }

    /**
     * @return string
     */
    public function render(TwigEngine $templating, $intention = 'publish'){
        $center = array(
            'lat'   => $this->parameters['center_lat'],
            'lon'   => $this->parameters['center_lon']
        );

        return $templating->render($this->template, array(
            'center' => $center,
            'zoom' => $this->parameters['zoom'],
            'intention' => $intention,
            'markers' => is_array($this->parameters['markers']) ? $this->parameters['markers'] : array()));
    }

}