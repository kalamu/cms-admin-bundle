<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;

class CmsMetasType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('label', 'text', array('label' => 'Label', 'required' => true, 'sonata_field_description' => "Label de la métadonnée"))
                ->add('name', 'text', array('label' => 'Nom', 'required' => true, 'sonata_field_description' => "Nom utilisé pour accéder à la métadonnée"))
                ->add('help', 'textarea', array('label' => "Texte d'aide", 'required' => false))
                ->add('type', 'choice', array(
                    'label' => "Type de formulaire", 
                    'choices' => $this->getFormTypeChoices(), 
                    'choices_as_values' => true,
                    'constraints'   => array(
                        new Choice(array('choices' =>$this->getFormTypeChoices()))
                    ),
                    'required' => true))
                ->add('required', 'choice', array(
                    'label' => 'Obligatoire', 
                    'choices' => array('Oui' => true, 'Non' => false), 
                    'choices_as_values' => true, 
                    'required' => true, 
                    'sonata_field_description' => "Rend le champ obligatoire",
                    'data' => false))
                ->add('duplicable', 'choice', array(
                    'label' => 'Duplicable', 
                    'required' => true, 
                    'choices' => array('Oui' => true, 'Non' => false), 
                    'choices_as_values' => true,
                    'sonata_field_description' => "Permet de définir plusieurs valeurs pour ce champ",
                    'data' => false));

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
//            'data_class' => 'AppBundle\Entity\Task',
        ));
    }

    public function getName() {
        return 'cms_metas';
    }
    
    /**
     * Retourne la liste des type de formulaire utilisable pour les métas
     * @return array
     */
    protected function getFormTypeChoices(){
        return array(
            'Texte simple'      => 'text',
            'Champ de texte'    => 'textarea',
            'Email'             => 'email',
            'Chiffre'           => 'integer',
            'URL'               => 'url',
            'Date'              => 'roho_datepicker',
            'Date/Heure'        => 'roho_datetimepicker',
            'Géolocalisation'   => 'geolocalisation_map'
        );
    }

}
