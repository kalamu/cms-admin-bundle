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
use Kalamu\CmsCoreBundle\ContentType\Interfaces\PublishStatusInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PublishStatusAdmin extends AbstractAdmin
{

    protected $translationDomain = 'kalamu';

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', CaseInsensitiveStringFilter::class)
            ->add('class')
            ->add('visible')
            ->add('default')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('class')
            ->add('visible')
            ->add('default')
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

        $choices = [];
        $metadatas = $this->getConfigurationPool()->getContainer()->get('doctrine')
                ->getManager()->getMetadataFactory()->getAllMetadata();
        foreach($metadatas as $meta){
            $refrection = $meta->getReflectionClass();
            if(!$refrection->isInstantiable() || !$refrection->implementsInterface(PublishStatusInterface::class)){
                continue;
            }

            $admin = $this->getConfigurationPool()->getAdminByClass($meta->getName());

            $choices[$admin ? $admin->getLabel() : $meta->getName()] = $meta->getName();
        }


        $formMapper
            ->add('title')
            ->add('class', ChoiceType::class, [
                'label' => 'Entity',
                'choices' => $choices,
                'choices_as_values' => true
            ])
            ->add('visible')
            ->add('default')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('class')
            ->add('visible')
            ->add('default')
        ;
    }
}
