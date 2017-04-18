<?php

namespace Kalamu\CmsAdminBundle\Widget\CMS;

use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Form;
/**
 * Widget pour afficher des Vidéo ou autre comptenu
 */
class EmbedWidget extends AbstractConfigurableElement
{

    protected $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function getTitle() {
        return 'cms.embed.title';
    }

    public function getDescription() {
        return 'cms.embed.description';
    }

    public function getForm(Form $form){
        $form->add("embed", 'textarea', array('label' => 'Code à intégrer',
            'sonata_field_description' => "Copiez-collez le code fourni pour intégrer la vidéo ou fonctionnalitée souhaitée.",
            'horizontal'=>false, 'label_attr' => array('class' => 'center-block text-left')));

        return $form;
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating){
        $embed = $this->paramaters['embed'];

        return $templating->render($this->template, array('embed' => $embed));
    }

}