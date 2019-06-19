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

use Kalamu\CmsAdminBundle\Validator\Constraints\ContrainsEmailsList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Form to define many contacts (used in contact form)
 */
class ContactFormContactType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('label', TextType::class, array('label' => 'Label', 'required' => true))
            ->add('emails', TextType::class, array(
                'required' => true,
                'sonata_field_description'    => "To define multiple recipient, you have to separate them with semi-colon ';'",
                'constraints'   => array(
                    new NotBlank(),
                    new ContrainsEmailsList()
                )));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array());
    }

}
