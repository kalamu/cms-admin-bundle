<?php

namespace Kalamu\CmsAdminBundle\Widget\CMS;

use Kalamu\CmsAdminBundle\Form\Type\GoogleMapMarkerType;
use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Form;

/**
 * Widget pour afficher une GoogleMap
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
     * Gère l'affichage du formulaire
     * @param TwigEngine $templating
     * @param type $form
     */
    public function renderConfigForm(TwigEngine $templating, Form $form){
       return $templating->render('KalamuCmsAdminBundle:Form/Widget:google_map.html.twig', array('form' => $form->createView(), 'widget' => $this));
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
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