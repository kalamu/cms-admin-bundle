<?php

namespace Kalamu\CmsAdminBundle\Controller\Admin;

use Kalamu\CmsAdminBundle\Form\Type\Config\MainConfigType;
use Kalamu\CmsAdminBundle\Form\Type\Config\TemplateConfigType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ConfigController extends Controller {

    /**
     * Configuration for main parameters of the website
     *
     * @param Request $Request
     * @return type
     */
    public function mainAction(Request $Request) {
        $config = $this->get('kalamu_dynamique_config')->get('cms_main_config', [
            'title' => 'Title of the website',
            'description' => 'Short description',
            'homepage_content' => [
                'display' => 'Index page of posts',
                'type' => 'post',
                'identifier' => 'index',
                'context' => null
            ],
            'search_engine_allow' => false
        ]);

        $form = $this->createForm(MainConfigType::class, $config);
        $form->handleRequest($Request);
        if ($form->isValid()) {
            $this->get('kalamu_dynamique_config')->set('cms_main_config', $form->getData());
            return $this->redirectToRoute('dynamique_configurator', ['part' => 'cms_main']);
        }

        $base_template = $this->getParameter('kalamu_dynamique_config.base_template');
        return $this->render('KalamuCmsAdminBundle:Config:main.html.twig', array(
            'base_template' => $base_template,
            'form' => $form->createView())
        );
    }


    /**
     * Template configuration
     * @param Request $Request
     * @return type
     */
    public function templateAction(Request $Request) {

        $config = $this->get('kalamu_dynamique_config')->get('default_template', array(
            'analytics' => null,
            'footer_template' => null,
        ));

        $form = $this->createForm(TemplateConfigType::class, $config);

        $form->handleRequest($Request);
        if ($form->isValid()) {
            $this->get('kalamu_dynamique_config')->set('default_template', $form->getData());
        }

        $base_template = $this->getParameter('kalamu_dynamique_config.base_template');
        return $this->render('KalamuCmsAdminBundle:Config:default_template.html.twig', array(
            'base_template' => $base_template,
            'title' => "Template configuration",
            'icon' => 'eye',
            'form' => $form->createView())
        );
    }

}
