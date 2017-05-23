<?php

namespace Kalamu\CmsAdminBundle\Widget\CMS;

use Kalamu\CmsAdminBundle\Form\Type\IsotopeItemType;
use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;

/**
 * Widget permettant d'afficher une liste de cards filtrable avec ziziautop
 */
class IsotopeCardsWidget extends AbstractConfigurableElement
{

    protected $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function getTitle() {
        return 'cms.isotope_cards.title';
    }

    public function getDescription() {
        return 'cms.isotope_cards.description';
    }

    public function getForm(Form $form){
        $form->add("title", TextType::class, array('label' => 'Titre', 'required' => true, 'horizontal'=>false, 'label_attr' => array('class' => 'center-block text-left')));

        $form->add("elements", CollectionType::class, array(
            'type'  => IsotopeItemType::class,
            'label' => 'Elements',
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'horizontal'=>false,
            'label_attr' => array('class' => 'center-block text-left'),
            'options' => array(
                'required'  => false,
                'horizontal'=>false,
            )
        ));

        return $form;
    }

    /**
     * Gère l'affichage du formulaire
     * @param TwigEngine $templating
     * @param type $form
     */
    public function renderConfigForm(TwigEngine $templating, Form $form){
       return $templating->render('KalamuCmsAdminBundle:Form/Widget:isotope_cards.html.twig', array('form' => $form->createView(), 'widget' => $this));
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating, $intention = 'edit'){

        if('publish' == $intention){
            $template = $this->template;
        }else{
            $template = 'KalamuCmsAdminBundle:Widget/CMS:isotope_cards.html.twig';
        }

        $this->parameters['types'] = array();
        $x = 0;
        foreach($this->parameters['elements'] as $i => $element){
//            var_dump($element['type']);
            $types = array_filter(explode(';', $element['type']), 'trim');
            $this->parameters['elements'][$i]['types'] = $types;
            foreach($types as $type){
                if(!isset($this->parameters['types'][$type])){
                    $this->parameters['types'][$type] = 'isotop-cat-'.$x++;
                }
            }
        }
//        var_dump($this->parameters['types']);die();

        return $templating->render($template, $this->parameters);
    }

}