<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
                ->add('latitude', TextType::class, array('required' => $options['required'], 'constraints' => [
                    new Range(['min' => -180, 'max' => 180])
                ]))
                ->add('longitude', TextType::class, array('required' => $options['required'], 'constraints' => [
                    new Range(['min' => -180, 'max' => 180])
                ]))
                ->add('srid', TextType::class, array('required' => $options['required'], 'label' => 'SRID'));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getParent()
    {
        return FormType::class;
    }

}
