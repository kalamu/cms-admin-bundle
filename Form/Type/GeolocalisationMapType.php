<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Description of GeolocalisationMapType
 *
 * @author johan
 */
class GeolocalisationMapType extends AbstractType
{
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('latitude', 'text', array('required' => $options['required'], 'constraints' => [
                    new Range(['min' => -180, 'max' => 180])
                ]))
                ->add('longitude', 'text', array('required' => $options['required'], 'constraints' => [
                    new Range(['min' => -180, 'max' => 180])
                ]))
                ->add('srid', 'text', array('required' => $options['required'], 'label' => 'SRID'));
        
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
        return 'geolocalisation_map';
    }
    
    
}
