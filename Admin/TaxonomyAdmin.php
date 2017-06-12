<?php

namespace Kalamu\CmsAdminBundle\Admin;

use Roho\CmsBundle\ContentType\Interfaces\ClassifiableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TaxonomyAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('libelle')
            ->add('slug')
            ->add('apply_on')
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
            ->add('apply_on', ChoiceType::class, [
                'required'  => true,
                'choices'   => $this->getClassificationInterfaces(),
                'choices_as_values' => true,
                'multiple'  => true,
                'expanded'  => true
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
            ->add('apply_on', 'array')
        ;
    }

    /**
     * Retourne les entités du CMS qui implémentes l'interface Classificable
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
