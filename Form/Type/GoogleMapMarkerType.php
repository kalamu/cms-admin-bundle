<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class GoogleMapMarkerType extends AbstractType
{
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('latitude', 'hidden', array('required' => true))
                ->add('longitude', 'hidden', array('required' => true))
                ->add('titre', 'text', array('required' => false, 'label' => 'Titre'))
                ->add('description', 'textarea', array('required' => false))
                ->add('default_open', 'choice', array(
                    'label'     => "Ouvert par défaut",
                    'choices'   => array('Oui' => true, 'Non' => false),
                    'choices_as_values' => true,
                    'expanded'  => true,
                    'data'      => true,
                    'required' => false
                ));
        
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
        return 'google_map_marker';
    }
}