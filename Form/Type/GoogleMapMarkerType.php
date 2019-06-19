<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoogleMapMarkerType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('latitude', HiddenType::class, array('required' => true))
                ->add('longitude', HiddenType::class, array('required' => true))
                ->add('titre', TextType::class, array('required' => false, 'label' => 'Titre'))
                ->add('description', TextareaType::class, array('required' => false))
                ->add('default_open', ChoiceType::class, array(
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
        return FormType::class;
    }
}