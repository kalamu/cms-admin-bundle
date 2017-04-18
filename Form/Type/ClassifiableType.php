<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Kalamu\CmsAdminBundle\Manager\ContentTypeManager;
use Doctrine\ORM\EntityRepository;

/**
 * Formulaire pour sélectionner les termes d'une entitiée classifiable
 */
class ClassifiableType extends AbstractType
{

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    public function __construct(Registry $doctrine){
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('cms_type')
            ->setDefaults(array(
                'class'         => 'KalamuCmsAdminBundle:Term',
                'multiple'      => true,
                'group_by'      => 'taxonomy',
                'expanded'      => true
            ))
            ->setAllowedTypes('cms_type', array('string'));

        $doctrine = $this->doctrine;

        $resolver->setDefault('query_builder', function(Options $options) use (&$doctrine) {

            $taxonomies = array();
            foreach($doctrine->getManager()->getRepository('KalamuCmsAdminBundle:Taxonomy')->findAll() as $taxonomy){
                if(in_array($options['cms_type'], $taxonomy->getApplyOn())){
                    $taxonomies[] = $taxonomy->getId();
                }
            }

            return function (EntityRepository $er) use ($taxonomies) {
                return $er->createQueryBuilder('t')
                        ->leftJoin('t.taxonomy', 'tax')
                        ->where('tax.id IN (:taxonomies)')
                        ->orderBy('t.libelle', 'ASC')
                        ->setParameter('taxonomies', $taxonomies);
            };

        });
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        if (method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix')) {
            return 'Symfony\Component\Form\Extension\Core\Type\ChoiceType';
        }

        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'classifiable';
    }
}
