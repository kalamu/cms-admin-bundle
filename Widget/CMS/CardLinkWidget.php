<?php

namespace Kalamu\CmsAdminBundle\Widget\CMS;

use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Form;

/**
 * Widget permettant d'afficher un lien sous forme de card
 */
class CardLinkWidget extends AbstractConfigurableElement
{

    protected $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function getTitle() {
        return 'cms.card_link.title';
    }

    public function getDescription() {
        return 'cms.card_link.description';
    }

    public function getForm(Form $form){
        $form->add("title", 'text', array('label' => 'Titre', 'required' => true, 'horizontal'=>false, 'label_attr' => array('class' => 'center-block text-left')));
        $form->add("image", 'elfinder', array('label' => 'Image', 'instance' => 'img_cms', 'elfinder_select_mode' => 'image', 'required' => false, 'horizontal'=>false, 'label_attr' => array('class' => 'center-block text-left')));
        $form->add("url", 'elfinder', array('label' => 'Document', 'instance' => 'docs_cms', 'elfinder_select_mode' => 'url', 'required' => true, 'horizontal'=>false, 'label_attr' => array('class' => 'center-block text-left')));
        return $form;
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating, $intention = 'edit'){

        if('publish' == $intention){
            $template = $this->template;
        }else{
            $template = 'KalamuCmsAdminBundle:Widget/CMS:card_link.html.twig';
        }

        return $templating->render($template, $this->paramaters);
    }

}