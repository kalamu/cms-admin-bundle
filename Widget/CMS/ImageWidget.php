<?php

namespace Kalamu\CmsAdminBundle\Widget\CMS;

use Kalamu\CmsAdminBundle\Form\Type\ElfinderType;
use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;

/**
 * Widget permettant d'insérer une image
 */
class ImageWidget extends AbstractConfigurableElement
{

    protected $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function getTitle() {
        return 'cms.image.title';
    }

    public function getDescription() {
        return 'cms.image.description';
    }

    public function getForm(Form $form){
        $form->add("title", TextType::class, array('label' => 'Titre'));
        $form->add("url", ElfinderType::class, array('label' => '', 'instance' => 'img_cms', 'elfinder_select_mode' => 'image', 'label_render' => false, 'label_attr' => array('class' => 'center-block text-left')));
        $form->add("alt", TextType::class, array('label' => 'Texte alternatif', 'required' => false, 'extra_fields_message' => "Texte affiché si l'image ne se charge pas."));
        $form->add("align", ChoiceType::class, array(
            'label'     => 'Alignement',
            'choices'   => array(
                'Aucun' => null,
                'Gauche'    => 'left',
                'Droite'    => 'right',
                'Centre'    => 'center'
            ),
            'choices_as_values' => true,
            'required'  => true
            ));
        return $form;
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating){
        return $templating->render($this->template, $this->paramaters);
    }

}