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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WysiwygDashboardType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'attr'  => array(
                'class' => 'hidden'
            )
        ));
    }

    public function getParent() {
        return TextareaType::class;
    }

}