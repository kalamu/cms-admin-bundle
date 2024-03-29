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

use Kalamu\CmsCoreBundle\ContentType\Interfaces\ClassifiableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TaxonomyAdmin extends AbstractAdmin
{

    protected $translationDomain = 'kalamu';

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('libelle')
            ->add('slug')
            ->add('content_type')
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
            ->add('libelle')
            ->add('slug', TextType::class, ['required' => false])
            ->add('content_type', ChoiceType::class, [
                'required'  => false,
                'choices'   => $this->getClassificationInterfaces(),
                'choices_as_values' => true,
                'multiple'  => false,
                'expanded'  => true,
                'label' => 'Apply On'
            ])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('libelle')
            ->add('slug')
            ->add('content_type')
        ;
    }

    /**
     * Get list of classifiable entities that are registered in the CMS
     * @return array
     */
    protected function getClassificationInterfaces(){
        $choices = [];
        $metadatas = $this->getConfigurationPool()->getContainer()->get('doctrine')
                ->getManager()->getMetadataFactory()->getAllMetadata();
        foreach($metadatas as $meta){
            $refrection = $meta->getReflectionClass();
            if(!$refrection->isInstantiable() || !$refrection->implementsInterface(ClassifiableInterface::class)){
                continue;
            }

            $admin = $this->getConfigurationPool()->getAdminByClass($meta->getName());

            $choices[$admin ? $admin->getLabel() : $meta->getName()] = $meta->getName();
        }

        return $choices;
    }
}
