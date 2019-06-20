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

use Kalamu\CmsAdminBundle\Form\Transformer\RestoreRolesTransformer;
use Kalamu\CmsAdminBundle\Security\EditableRolesBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SecurityRolesType extends AbstractType
{
    protected $rolesBuilder;

    /**
     * @param EditableRolesBuilder $rolesBuilder
     */
    public function __construct(EditableRolesBuilder $rolesBuilder)
    {
        $this->rolesBuilder = $rolesBuilder;
    }

    /**
     * Add the datatransformer to keep possible roles that the user have but
     * that are not present on the form
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $tranformer = new RestoreRolesTransformer($this->rolesBuilder);

        // GET METHOD
        $formBuilder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($tranformer) {
            $tranformer->setOriginalRoles($event->getData());
        });

        // POST METHOD
        $formBuilder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) use ($tranformer) {
            $tranformer->setOriginalRoles($event->getForm()->getData());
        });

        $formBuilder->addModelTransformer($tranformer);
    }

    /**
     * Build parameters for rendering the form
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = $view->vars['attr'];

        if (isset($attr['class']) && empty($attr['class'])) {
            $attr['class'] = 'sonata-medium';
        }

        $view->vars['attr'] = $attr;
        $view->vars['hierarchicalRoles'] = $options['hierarchicalRoles'];
        $selectedRoles = $form->getData();
        if (!empty($selectedRoles)) {
            $view->vars['selected_choices'] = $selectedRoles;
        } else {
            $view->vars['selected_choices'] = '';
        }
    }

    /**
     * Configure the options for the form :
     *      - choices : flat list of existing roles (used by symfony)
     *      - rolesByGroup : list of role orgnised by groups (used for the template)
     *
     */
    public function configureOptions(OptionsResolver $resolver) {

        $roles = $this->rolesBuilder->getRoles();
        $rolesByGroup = $this->rolesBuilder->getRolesByGroups();

        $resolver->setDefaults(array(
            'choices' => function (Options $options, $parentChoices) use ($roles) {
                return empty($parentChoices) ? $roles : array();
            },
            'hierarchicalRoles' => $rolesByGroup,
            'data_class' => null,
            'multiple' => true
        ));

    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }
}