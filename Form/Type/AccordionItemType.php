<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class AccordionItemType extends AbstractType
{
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
                ->add('title', 'text', array('required' => true))
                ->add('icon', 'text', array('required' => false))
                ->add('contenu', 'textarea', array('required' => false));
        
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
        return 'accordion_item';
    }
}