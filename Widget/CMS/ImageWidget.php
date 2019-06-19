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

use Kalamu\CmsAdminBundle\Form\Type\CmsLinkSelectorType;
use Kalamu\CmsAdminBundle\Form\Type\ElfinderType;
use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Sonata\AdminBundle\Form\Type\ChoiceFieldMaskType;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Form;

/**
 * Widget to display an image
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
        $form->add("title", TextType::class, array('label' => 'Title'));
        $form->add("url", ElfinderType::class, array('label' => 'Image', 'instance' => 'img_cms', 'elfinder_select_mode' => 'image', 'label_render' => false, 'label_attr' => array('class' => 'center-block text-left')));
        $form->add("alt", TextType::class, array('label' => 'Alternative text', 'required' => false, 'extra_fields_message' => "Used by blind people or when the image doesn't load."));
        $form->add("lien", ChoiceFieldMaskType::class, [
            'label' => "Link on the image",
            'choices' => [
                "no link" => false,
                "Link to the image file" => 'self',
                "Link to an internal page" => 'other',
                "Link to an URL" => 'url'
            ],
            'choices_as_values' => true,
            'map' => [
                'other' => ['cms_link'],
                'url' => ['direct_url']
            ]
        ]);
        $form->add('cms_link', CmsLinkSelectorType::class, ['label' => 'Link', 'required' => false]);
        $form->add('direct_url', UrlType::class, ['label' => 'URL', 'required' => false]);
        $form->add("align", ChoiceType::class, array(
            'label'     => 'Alignment',
            'choices'   => array(
                'None' => null,
                'Left'    => 'left',
                'Right'    => 'right',
                'Center'    => 'center'
            ),
            'choices_as_values' => true,
            'required'  => true
            ));
        return $form;
    }

    /**
     * @return string
     */
    public function render(TwigEngine $templating){
        return $templating->render($this->template, $this->parameters);
    }

}