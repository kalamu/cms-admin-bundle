<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IsotopeItemType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
                ->add('title', TextType::class, array('required' => true, 'label' => 'Titre'))
                ->add('type', TextType::class, array('required' => false, 'sonata_field_description' => "Séparez les types par des ';' s'il y en a plusieur."))
                ->add('image', ElfinderType::class, array('required' => false, 'instance' => 'img_cms', 'elfinder_select_mode' => 'image'))
                ->add('description', TextareaType::class, array('required' => false));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getParent()
    {
        return FormType::class;
    }

}