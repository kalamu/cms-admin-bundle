<?php

namespace Kalamu\CmsAdminBundle\Widget\CMS;

use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Form;

/**
 * Widget affichant un accordion
 */
class AccordionWidget extends AbstractConfigurableElement
{

    protected $templates;

    protected $default_display_modes = array(
        'accordion'     => array(
            'title'     => 'Accordéon déroulant',
            'template'  => 'KalamuCmsAdminBundle:Widget/CMS:accordion.html.twig'),
        'blocks'     => array(
            'title'     => 'Blocks',
            'template'  => 'KalamuCmsAdminBundle:Widget/CMS:blocks.html.twig'),
    );

    public function __construct($templates) {
        $this->templates = $templates;
    }

    public function getTitle() {
        return 'cms.accordion.title';
    }

    public function getDescription() {
        return 'cms.accordion.description';
    }

    public function getForm(Form $form){
        $form->add('title', 'text', array('label' => 'Titre'));
        $form->add("elements", 'collection', array(
            'type'  => 'accordion_item',
            'label_render' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'horizontal'=>false,
            'options' => array(
                'required'  => false,
                'horizontal'=>false,
            )
        ));

        $choices = array();
        foreach($this->default_display_modes as $key => $info){
            $choices[$info['title']] = $key;
        }
        $form->add('display_mode', 'choice', array(
            'choices' => $choices,
            'choices_as_values' => true,
            'label' => "Type d'affichage"
        ));

        return $form;
    }

    /**
     * Gère l'affichage du formulaire
     * @param TwigEngine $templating
     * @param type $form
     */
    public function renderConfigForm(TwigEngine $templating, Form $form){
       return $templating->render('KalamuCmsAdminBundle:Form/Widget:accordion.html.twig', array('form' => $form->createView(), 'widget' => $this));
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating, $intention = 'edit'){
        $elements = $this->paramaters['elements'];
        $display = $this->paramaters['display_mode'];

        if('publish' == $intention){
            $template = isset($this->templates[$display]) ? $this->templates[$display]['template'] : $this->default_display_modes[$display]['template'];
        }else{
            $template = isset($this->default_display_modes[$display]) ? $this->default_display_modes[$display]['template'] : $this->templates[$display]['template'];
        }

        return $templating->render($template, array('elements' => $elements, 'title' => $this->paramaters['title']));
    }

}