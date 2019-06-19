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

use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Contact form
 */
class FormContactType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('selectable_recipient', ChoiceType::class, array(
            'label' => 'Selectable recipient',
            'choices'   => array('Yes' => true, 'No' => false),
            'choices_as_values' => true,
            'data'  => false,
            'sonata_help'    => "Allow the user to select the recipient"
        ));
        $builder->add("simple_recipient", CollectionType::class, array(
            'type' => EmailType::class,
            'options' => array(
                'required' => true,
                'constraints' => array(
                    new NotBlank(),
                    new Email()
                ),
            ),
            'allow_add' => true,
            'allow_delete' => true,
            'label' => 'Email address',
            'sonata_help'    => "Destination email address for this form.",
        ));
        $builder->add("recipient_choice_label", TextType::class, array(
            'label' => 'Display name for recipeint selector',
            'data'  => "Select you contact",
            'required' => true));
        $builder->add("recipient_choice", CollectionType::class, array(
            'type' => ContactFormContactType::class,
            'label' => 'Recipeint list',
            'allow_add' => true,
            'allow_delete' => true));


        $builder->add("success", TextType::class, array(
            'label' => 'Message for success sending',
            'data'  => "Your message has been send.",
            'required' => true,
            'constraints' => array(
                new NotBlank(),
            )
        ));
        $builder->add("error", TextType::class, array(
            'label' => "Message for failed sending",
            'data'  => "We are sorry, the sending has failed.",
            'required' => true,
            'constraints' => array(
                new NotBlank(),
            )
        ));

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'constraints' => new Callback(['callback' => [$this, 'validator']])
        ));
    }

    public function validator($datas, ExecutionContextInterface $context){
        if($datas['selectable_recipient']){
            if(!count($datas['recipient_choice'])){
                $context->buildViolation('You must define at least one destination address.')
                    ->atPath('recipient_choice')
                    ->addViolation();
            }
        }else{
            if(!count($datas['simple_recipient'])){
                $context->buildViolation('You must define at least one destination address.')
                    ->atPath('simple_recipient')
                    ->addViolation();
            }
        }
    }

}
