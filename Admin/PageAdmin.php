<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Admin;

use Kalamu\CmsAdminBundle\Form\Filter\CaseInsensitiveStringFilter;
use Kalamu\CmsAdminBundle\Form\Type\WysiwygDashboardType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PageAdmin extends AbstractAdmin
{

    protected $translationDomain = 'kalamu';

    public function getTemplate($name) {
        switch($name){
            case 'show':
                return 'KalamuCmsAdminBundle:Page:base_show.html.twig';
        }

        return parent::getTemplate($name);
    }


    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title', CaseInsensitiveStringFilter::class)
            ->add('slug', CaseInsensitiveStringFilter::class)
            ->add('content', CaseInsensitiveStringFilter::class)
            ->add('created_at')
            ->add('published_at')
            ->add('created_by')
            ->add('updated_by')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
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

        $ContentTypeManager = $this->getConfigurationPool()->getContainer()->get('kalamu_cms_core.content_type.manager');
        $managerType = $ContentTypeManager->getType('page');

        if($managerType->getTemplates()){
            $templates = [];
            foreach($managerType->getTemplates() as $key => $template){
                $templates[$template['title']] = $key;
            }

            foreach($managerType->getContexts() as $context){
                foreach($managerType->getTemplates($context) as $key =>  $template){
                    $templates[$template['title']] = $key;
                }
            }
        }

        $formMapper
            ->with("Page", ['class' => 'col-md-9'])
                ->add('title', TextType::class)
                ->add('slug', TextType::class, ['required' => false])
                ->add('content', WysiwygDashboardType::class)
            ->end()
            ->with("Infos", ['class' => 'col-md-3']);

        if(isset($templates) && count($templates)){
            $formMapper
                ->add('template', ChoiceType::class, [
                    'choices' => $templates,
                    'choices_as_values'  => true,
                    'required' => false
                ]);
        }

        $formMapper
                ->add('contextPublication', EntityType::class, [
                    'class' => 'KalamuCmsAdminBundle:ContextPublication',
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true
                ])
                ->add('parent', EntityType::class, [
                    'class' => 'KalamuCmsAdminBundle:Page',
                    'required' => false,
                    'label' => 'Parent page',
                    'query_builder' => function($repository) use ($admin){
                        $base = $repository->createQueryBuilder('p')
                                ->orderBy('p.title', 'ASC');
                        if($admin->getSubject() && $admin->getSubject()->getId()){
                            return $base->where('p.id != :self')
                                    ->setParameter('self', $admin->getSubject()->getId());
                        }else{
                            return $base;
                        }
                    },

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
                ->add('content', null, ['template' => 'KalamuCmsAdminBundle:Content:_wysiwyg_dashboard.html.twig'])
            ->end()
            ->with('Infos', ['class' => 'col-md-3'])
                ->add('template')
                ->add('created_at')
                ->add('updated_at')
                ->add('published_at')
                ->add('created_by')
                ->add('updated_by')
                ->add('', null, ['template' => 'KalamuCmsAdminBundle:Page:show_in_front.html.twig'])
            ->end()
        ;
    }
}
