<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsMetasGroupType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, array('label' => 'Nom', 'sonata_field_description' => "Utilisé comme référence dans les templates", 'required' => true))
                ->add('label', TextType::class, array('label' => 'Titre du groupe', 'required' => true))
                ->add('group', CollectionType::class, array(
                    'type' => 'cms_metas',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'delete_empty' => true,
                    'label' => "Champs",
                    'prototype_name' => '__name_group__'));

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
        ));
    }

    public function getName() {
        return 'cms_metas_group';
    }

}
