<?php

namespace Kalamu\CmsAdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MenuAdmin extends AbstractAdmin
{

    protected $translationDomain = 'kalamu';

    public function getTemplate($name) {
        if($name === 'edit'){
            return 'KalamuCmsAdminBundle:Menu:base_edit.html.twig';
        }

        return parent::getTemplate($name);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('slug')
            ->add('place')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('slug')
            ->add('place')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $places = $this->getConfigurationPool()->getContainer()->getParameter('roho_cms.menus');

        $formMapper
            ->with('Menu', ['class' => 'col-md-9'])
                ->add('title', TextType::class)
                ->add('json_items', HiddenType::class)
            ->end()
            ->with('Infos', ['class' => 'col-md-3'])
                ->add('place', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
                    'choices' => array_flip($places),
                    'choices_as_values' => true,
                    'help' => "Permet d'affecter le menu à un emplacement du thème"
                ])
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('slug')
            ->add('place')
        ;
    }
}
