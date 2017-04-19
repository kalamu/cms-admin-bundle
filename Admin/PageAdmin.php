<?php

namespace Kalamu\CmsAdminBundle\Admin;

use Kalamu\CmsAdminBundle\Form\Type\WysiwygDashboardType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PageAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('slug')
            ->add('contenu')
//            ->add('template')
            ->add('created_at')
//            ->add('updated_at')
            ->add('published_at')
            ->add('created_by')
            ->add('updated_by')
//            ->add('metas')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title')
            ->add('slug')
            ->add('created_at')
            ->add('published_at')
            ->add('created_by')
            ->add('updated_by')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $admin = $this;

        $ContentTypeManager = $this->getConfigurationPool()->getContainer()->get('roho_cms.content_type.manager');
        $managerType = $ContentTypeManager->getType('page');

        if(($templates = $managerType->getTemplates())){
            foreach($managerType->getContexts() as $context){
                $templates = array_merge($templates, $managerType->getTemplates($context));
            }
        }


        $formMapper
            ->with("Page", ['class' => 'col-md-9'])
                ->add('title', TextType::class)
                ->add('slug')
                ->add('contenu', WysiwygDashboardType::class)
            ->end()
            ->with("Infos", ['class' => 'col-md-3'])
                ->add('template', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
                    'choices' => array_flip($templates),
                    'choices_as_values'  => true,
                    'required' => false
                ])
                ->add('contextPublication', EntityType::class, [
                    'class' => 'KalamuCmsAdminBundle:ContextPublication',
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true
                ])
                ->add('publishStatus', EntityType::class, [
                    'class' => 'KalamuCmsAdminBundle:PublishStatus',
                    'query_builder' => function($repository) use ($admin){
                        return $repository->createQueryBuilder('s')
                                ->where('s.class = :class')
                                ->setParameter('class', $admin->getClass());
                    }
                ])
                ->add('published_at', DateTimePickerType::class, ['required' => false])
//                ->add('metas')
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('Page', ['class' => 'col-md-9'])
                ->add('title')
                ->add('slug')
                ->add('contenu', null, ['template' => 'KalamuCmsAdminBundle:Content:_wysiwyg_dashboard.html.twig'])
            ->end()
            ->with('Infos', ['class' => 'col-md-3'])
                ->add('template')
                ->add('created_at')
                ->add('updated_at')
                ->add('published_at')
                ->add('created_by')
                ->add('updated_by')
            ->end()
        ;
    }
}