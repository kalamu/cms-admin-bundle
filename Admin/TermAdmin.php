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

use Kalamu\CmsAdminBundle\Form\Filter\CaseInsensitiveStringFilter;
use Kalamu\CmsAdminBundle\Form\Type\ElfinderType;
use Kalamu\CmsAdminBundle\Form\Type\WysiwygDashboardType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TermAdmin extends AbstractAdmin
{

    protected $translationDomain = 'kalamu';

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('libelle', CaseInsensitiveStringFilter::class)
            ->add('slug', CaseInsensitiveStringFilter::class)
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('libelle')
            ->add('slug')
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
        $formMapper
            ->add('taxonomy')
            ->add('libelle')
            ->add('slug', TextType::class, ['required' => false])
            ->add('parent')
            ->add('image', ElfinderType::class, ['instance' => 'img_cms', 'required' => false, 'elfinder_select_mode' => 'image'])
            ->add('resume', TextareaType::class, ['required' => false])
            ->add('description', WysiwygDashboardType::class, ['required' => false])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
//            ->add('id')
            ->add('libelle')
            ->add('slug')
            ->add('image')
            ->add('resume')
            ->add('description')
        ;
    }
}
