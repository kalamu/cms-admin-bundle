<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Kalamu\CmsAdminBundle\Validator\Constraints\ContrainsEmailsList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Formulaire pour définir différents contacts sur le formulaire de contact
 */
class ContactFormContactType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('label', TextType::class, array('label' => 'Label', 'required' => true))
            ->add('emails', TextType::class, array(
                'required' => true,
                'sonata_field_description'    => "Pour définir plusieurs destinataire vous pouvez les séparer par des point-virgule.",
                'constraints'   => array(
                    new NotBlank(),
                    new ContrainsEmailsList()
                )));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array());
    }

}
