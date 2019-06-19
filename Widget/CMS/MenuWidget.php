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

use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
        $form->add("menu", EntityType::class, array('label' => 'Menu',
            'class' => 'KalamuCmsAdminBundle:Menu',
        ));

        return $form;
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating){
        $menu = $this->parameters['menu'];

        return $templating->render($this->template, array('menu' => $menu));
    }

}