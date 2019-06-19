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

use Kalamu\CmsAdminBundle\Form\Type\AccordionItemType;
use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $form->add('title', TextType::class, array('label' => 'Titre'));
        $form->add("elements", CollectionType::class, array(
            'type'  => AccordionItemType::class,
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
        $form->add('display_mode', ChoiceType::class, array(
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
        $elements = $this->parameters['elements'];
        $display = $this->parameters['display_mode'];

        if('publish' == $intention){
            $template = isset($this->templates[$display]) ? $this->templates[$display]['template'] : $this->default_display_modes[$display]['template'];
        }else{
            $template = isset($this->default_display_modes[$display]) ? $this->default_display_modes[$display]['template'] : $this->templates[$display]['template'];
        }

        return $templating->render($template, array('elements' => $elements, 'title' => $this->parameters['title']));
    }

}