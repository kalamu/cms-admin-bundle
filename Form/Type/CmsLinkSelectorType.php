<?php

namespace Kalamu\CmsAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class CmsLinkSelectorType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('display', 'hidden')
            ->add('type', 'hidden')
            ->add('identifier', 'hidden')
            ->add('context', 'hidden');

    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
        ));
    }

    public function getName() {
        return 'cms_link_selector';
    }
    
}
