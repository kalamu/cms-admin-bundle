<?php

namespace Kalamu\CmsAdminBundle\Widget\CMS;

use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Form;
/**
 * Widget permettant de sélectionner un menu
 */
class MenuWidget extends AbstractConfigurableElement
{

    protected $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function getTitle() {
        return 'cms.menu.title';
    }

    public function getDescription() {
        return 'cms.menu.description';
    }

    public function getForm(Form $form){
        $form->add("menu", 'entity', array('label' => 'Menu',
            'class' => 'KalamuCmsAdminBundle:Menu',
        ));

        return $form;
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating){
        $menu = $this->paramaters['menu'];

        return $templating->render($this->template, array('menu' => $menu));
    }

}