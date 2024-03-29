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

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * FormType to select entities that implements PublishStatusInterface
 */
class PublishStatusInterfacesType extends AbstractType
{
    /**
     * @var Registry
     */
    protected $doctrine;

    public function __construct(Registry $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $choices = array('' => '');

        $metadatas = $this->doctrine->getManager()->getMetadataFactory()->getAllMetadata();
        foreach($metadatas as $meta){
            $refrection = $meta->getReflectionClass();
            if(!$refrection->isInstantiable() || !$refrection->implementsInterface('Kalamu\CmsAdminBundle\ContentType\Interfaces\PublishStatusInterface')){
                continue;
            }

            $choices[$meta->getName()] = $meta->getName();
        }

        $resolver->setDefault('choices', $choices);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

}