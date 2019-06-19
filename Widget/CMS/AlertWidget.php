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

use Kalamu\CmsAdminBundle\Form\Type\WysiwygType;
use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Form;

/**
 * Widget affichant un block alert bootstrap
 */
class AlertWidget extends AbstractConfigurableElement
{

    protected $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function getTitle() {
        return 'cms.alert.title';
    }

    public function getDescription() {
        return 'cms.alert.description';
    }

    public function getForm(Form $form){
        $form->add('type', ChoiceType::class, array(
            'choices' => ['Vert' => 'success', 'Orange' => 'warning', 'Rouge' => 'danger'],
            'choices_as_values' => true,
            'label' => "Type d'affichage",
            'required' => true
        ));
        $form->add('contenu', WysiwygType::class, [
            'label' => 'Contenu',
            'required' => true
        ]);

        return $form;
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating, $intention = 'edit'){
        $this->parameters['intention'] = $intention;

        return $templating->render($this->template,$this->parameters);
    }

}