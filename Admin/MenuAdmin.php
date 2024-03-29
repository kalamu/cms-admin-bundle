<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        $places = $this->getConfigurationPool()->getContainer()->getParameter('kalamu_cms_core.menus');

        $formMapper
            ->with('Menu', ['class' => 'col-md-9'])
                ->add('title', TextType::class)
                ->add('json_items', HiddenType::class)
            ->end()
            ->with('Infos', ['class' => 'col-md-3'])
                ->add('place', ChoiceType::class, [
                    'choices' => array_flip($places),
                    'choices_as_values' => true,
                    'help' => "Define where the menu must appear in the theme",
                    'required' => false,
                    'placeholder' => '...'
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
