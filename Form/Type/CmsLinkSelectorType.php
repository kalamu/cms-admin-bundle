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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CmsLinkSelectorType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('display', HiddenType::class)
            ->add('type', HiddenType::class)
            ->add('identifier', HiddenType::class)
            ->add('context', HiddenType::class)
            ->add('url', HiddenType::class);

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
        ));
    }

}
