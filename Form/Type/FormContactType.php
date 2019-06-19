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
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Formulaire pour administrer le widget "Formulaire de contact"
 */
class FormContactType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('selectable_destinataire', ChoiceType::class, array(
            'label' => 'Destinataire sélectionnable',
            'choices'   => array('Oui' => true, 'Non' => false),
            'choices_as_values' => true,
            'data'  => false,
            'sonata_help'    => "Permettre à l'utilisateur de sélectionner le destinataire du message"
        ));
        $builder->add("destinataire_simple", CollectionType::class, array(
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
            'label' => 'Adresses de destination',
            'sonata_help'    => "Adresse de destination pour les mails qui seront envoyés depuis ce formulaire.",
        ));
        $builder->add("label_choix_destinataire", TextType::class, array(
            'label' => 'Label du sélecteur de destinataire',
            'data'  => "Sélectionnez la personne que vous souhaitez contacter",
            'required' => true));
        $builder->add("choix_destinataire", CollectionType::class, array(
            'type' => ContactFormContactType::class,
            'label' => 'Liste des destinataires',
            'allow_add' => true,
            'allow_delete' => true));


        $builder->add("success", TextType::class, array(
            'label' => 'Message en cas de succès de l\'envoie',
            'data'  => "Votre message a bien été envoyé.",
            'required' => true,
            'constraints' => array(
                new NotBlank(),
            )
        ));
        $builder->add("error", TextType::class, array(
            'label' => "Message en cas d'erreur de l'envoie",
            'data'  => "Nous sommes désolés, un problème technique est survenue et votre mail n'a pu être envoyé.",
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
        if($datas['selectable_destinataire']){
            if(!count($datas['choix_destinataire'])){
                $context->buildViolation('Il doit y avoir au minimum une adresse de destination.')
                    ->atPath('choix_destinataire')
                    ->addViolation();
            }
        }else{
            if(!count($datas['destinataire_simple'])){
                $context->buildViolation('Il doit y avoir au minimum une adresse de destination.')
                    ->atPath('destinataire_simple')
                    ->addViolation();
            }
        }
    }

}
