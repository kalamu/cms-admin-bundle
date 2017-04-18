<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class IsotopeItemType extends AbstractType
{
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
                ->add('title', 'text', array('required' => true, 'label' => 'Titre'))
                ->add('type', 'text', array('required' => false, 'sonata_field_description' => "Séparez les types par des ';' s'il y en a plusieur."))
                ->add('image', 'elfinder', array('required' => false, 'instance' => 'img_cms', 'elfinder_select_mode' => 'image'))
                ->add('description', 'textarea', array('required' => false));
        
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
    }
    
    public function getParent()
    {
        return 'form';
    }

    public function getName()
    {
        return 'isotope_item';
    }
}