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
use Symfony\Component\Form\Form;
/**
 * Widget that display a WYSIWYG editor
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
        $form->add("content", WysiwygType::class, array('label' => 'Content', 'label_attr' => array('class' => 'center-block text-left')));

        return $form;
    }

    /**
     * @return string
     */
    public function render(TwigEngine $templating){
        $content = $this->parameters['content'];

        return $templating->render($this->template, array('content' => $content));
    }

}