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

use FOS\UserBundle\Doctrine\UserManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserAdmin extends AbstractAdmin
{

    /**
     * @var UserManager
     */
    protected $fosUserManager;

    public function setFosUserManager(UserManager $fosUserManager)
    {
        $this->fosUserManager = $fosUserManager;
    }

    public function preUpdate($object) {
        $this->updateFosManager($object);
    }

    public function prePersist($object) {
        $this->updateFosManager($object);
    }

    protected function updateFosManager($object)
    {
        $this->fosUserManager->updateCanonicalFields($object);
        $this->fosUserManager->updatePassword($object);
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('groups')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username', null, ['route'=>['name' => 'show']])
            ->add('email')
            ->add('enabled')
            ->add('groups', null, ['template' => 'KalamuCmsAdminBundle:User:list__groups.html.twig'])
            ->add('lastLogin')
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
        $formMapper
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('plain_password', PasswordType::class, [
                'required' => false
            ])
            ->add('super_admin')
            ->add('groups')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('lastLogin')
            ->add('passwordRequestedAt')
            ->add('roles')
        ;
    }
}
