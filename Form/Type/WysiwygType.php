<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WysiwygType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
    }
    
    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'wysiwyg';
    }
}