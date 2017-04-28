<?php

namespace Kalamu\CmsAdminBundle\Widget\CMS;

use Kalamu\CmsAdminBundle\Form\Type\WysiwygType;
use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Form;
/**
 * Widget affichant un formulaire WYSIWYG
 */
class WysiwygWidget extends AbstractConfigurableElement
{

    protected $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function getTitle() {
        return 'cms.wysiwyg.title';
    }

    public function getDescription() {
        return 'cms.wysiwyg.description';
    }

    public function getForm(Form $form){
        $form->add("content", WysiwygType::class, array('label' => 'Contenu', 'label_attr' => array('class' => 'center-block text-left')));

        return $form;
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating){
        $content = $this->paramaters['content'];

        return $templating->render($this->template, array('content' => $content));
    }

}