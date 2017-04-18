<?php

namespace Kalamu\CmsAdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Doctrine\ORM\EntityRepository;
use Roho\CoreBundle\Humanizer\EntityHumanizer;
use Kalamu\CmsAdminBundle\Manager\ContentTypeManager;
use Symfony\Component\Validator\Constraints;
use Roho\DynamiqueConfigBundle\Container\ParameterContainer;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Formulaire de base pour le contenus du CMS
 */
abstract class BaseContentType extends AbstractType {

    /**
     * Humaniser de l'entitée
     * @var \Roho\CoreBundle\Humanizer\EntityHumanizer
     */
    protected $humanizer;
    
    /**
     * Manager de l'entitée
     * @var \Kalamu\CmsAdminBundle\Manager\ContentManager
     */
    protected $manager;
    
    protected $content_type;

    /**
     * Container des paramètre éditable depuis l'admin
     * @var \Roho\DynamiqueConfigBundle\Container\ParameterContainer
     */
    protected $parameter_container;
    
    public function __construct(EntityHumanizer $humanizer) {
        $this->humanizer = $humanizer;
    }
    
    public function setContentTypeManager(ContentTypeManager $ContentTypeManager){
        $this->manager = $ContentTypeManager->getType($this->content_type);
    }
    
    public function setDynamiqueConfig(ParameterContainer $ParameterContainer){
        $this->parameter_container = $ParameterContainer;
    }

    /**
     * Retourne la configuration par défaut de tous les champs de l'entitée
     * @return array
     */
    public function getFieldsConfiguration() {
        $fields = array();
        
        $reflexion = $this->manager->getReflectionClass();
        
        if($reflexion->hasProperty('title')){
            $fields['title'] = array('type' => 'text', 'options' => array());
        }
        if($reflexion->hasProperty('slug')){
            $fields['slug'] = array('type' => 'text', 'options' => array('required' => false));
        }
        if($reflexion->hasProperty('resume')){
            $fields['resume'] = array('type' => 'textarea', 'options' => array('required' => false));
        }
        if($reflexion->hasProperty('contenu')){
            $fields['contenu'] = array('type' => 'wysiwyg_dashboard', 'options' => array('required' => false));
        }
        if($reflexion->hasProperty('image')){
            $fields['image'] = array('type' => 'elfinder', 'options' => array(
            'instance' => 'img_cms', 'required' => false, 'elfinder_select_mode' => 'image'));
        }
        
        if($reflexion->hasProperty('published_at')){
            $fields['published_at'] = array('type' => 'roho_datetimepicker', 'options' => array('required' => false));
        }
        
        if($reflexion->hasProperty('published_until')){
            $fields['published_until'] = array('type' => 'roho_datetimepicker', 'options' => array('required' => false));
        }
        if($reflexion->hasProperty('template') && ($templates = $this->manager->getTemplates())){
            foreach($this->manager->getContexts() as $context){
                $templates = array_merge($templates, $this->manager->getTemplates($context));
            }
            
            $fields['template'] = array('type' => 'choice', 'options' => array(
                'choices' => array_flip($templates),
                'choices_as_values'  => true,
                'required' => false
            ));
        }
        if($reflexion->hasProperty('publish_status')){
            $fields['publish_status'] = array('type' => 'entity', 'options' => array(
                'class' => 'RohoCmsBundle:PublishStatus',
                'query_builder' => function (EntityRepository $EntityRepository) use ($reflexion){
                    return $EntityRepository->createQueryBuilder('s')
                            ->where('s.class = :class')
                            ->setParameter(':class', $reflexion->getName());
                },
                'required' => true,
                'label' => "Statut de publication"));
        }
        if($reflexion->hasProperty('context_publication')){
            $fields['context_publication'] = array('type' => 'entity', 'options' => array(
                'class' => 'RohoCmsBundle:ContextPublication',
                'required' => false,
                'multiple' => true,
                'expanded' => true));
        }
        if($reflexion->hasProperty('terms')){
            $fields['terms'] = array('type' => 'classifiable', 'options' => array(
                'required'  => false,
                'cms_type'  => $this->content_type
            ));
        }
        
        if($reflexion->implementsInterface('Kalamu\CmsAdminBundle\ContentType\Interfaces\GeoLocalizableInterface')){
            $fields['latitude'] = array('type' => 'hidden', 'options' => array('required' => false));
            $fields['longitude'] = array('type' => 'hidden', 'options' => array('required' => false));
            $fields['srid'] = array('type' => 'hidden', 'options' => array('required' => false));
        }
        
        if($reflexion->implementsInterface('Kalamu\CmsAdminBundle\ContentType\Interfaces\AddressableInterface')){
            $fields['address_1'] = array('type' => 'textarea', 'options' => array('required' => false));
            $fields['address_2'] = array('type' => 'textarea', 'options' => array('required' => false));
            $fields['code_postal'] = array('type' => 'number', 'options' => array(
                'required' => false, 
                'scale' => 0,
                'constraints' => array(
                    new Constraints\Range(array(
                        'min' => 10000,
                        'max' => 99999
                    ))
                )));
            $fields['ville'] = array('type' => 'text', 'options' => array('required' => false));
            $fields['pays'] = array('type' => 'text', 'options' => array('required' => false));
        }
        
        return $fields;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fields = $this->getFieldsConfiguration();

        // création du builder
        foreach($options['display_fields'] as $field => $display){
            if(!$display || !isset($fields[$field])){
                continue;
            }

            if(!array_key_exists('label', $fields[$field]['options'])){
                $fields[$field]['options']['label'] = $this->humanizer->getProperty($field);
            }
            $builder->add($field, $fields[$field]['type'], $fields[$field]['options']);
        }
        
        $this->appendMetasFields($builder, $options);
        
        switch($options['intention']){
            case 'create':
                $builder->add('submit', 'submit', array('label' => 'Créer', 'attr' => array('class' => 'btn btn-success')));
                $builder->add('submit_add', 'submit', array('label' => 'Créer et nouveau', 'attr' => array('class' => 'btn btn-success')));
                break;
            case 'edit':
                 $builder->add('submit', 'submit', array('label' => 'Modifier', 'attr' => array('class' => 'btn btn-success')));
                break;
        }
    }
    
    public function appendMetasFields(FormBuilderInterface $builder, array $options){
        $reflexion = $this->manager->getReflectionClass();
        if(!$reflexion->implementsInterface('Kalamu\CmsAdminBundle\ContentType\Interfaces\CaracterizableInterface')){
            return; // n'implémente pas les méta
        }
        $config_metas = $this->getMetaConfig($options);
        if(!count($config_metas)){
            return; // pas de méta configuré
        }
        
        $builder->add('metas', null, array('compound' => true));
        
        foreach($config_metas as $configGroup){
            $builder->get('metas')->add($configGroup['name'], null, array('compound' => true, 'label' => $configGroup['label']));
            $groupBuilder = $builder->get('metas')->get($configGroup['name']);
            foreach($configGroup['group'] as $field){
                $groupBuilder->add($field['name'], $field['type'], array(
                    'label'         => $field['label'] ? $field['label'] : $field['name'],
                    'sonata_field_description'    => $field['help'] ? $field['help'] : null,
                    'required'      => $field['required']
                ));
            }
        }
    }
    
    /**
     * Retourne la config des métadonnées
     * @return array
     */
    protected function getMetaConfig(array $options){
        return $this->parameter_container->get('roho_cms_metas['.$this->content_type.']', array());
    }

}
