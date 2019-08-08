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

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Doctrine\UserManager;
use Kalamu\CmsAdminBundle\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserAdmin extends AbstractAdmin
{

    /**
     * @var UserManager
     */
    protected $fosUserManager;

    /**
     * @var EntityManagerInterface
     */
    protected $doctrine;

    public function setFosUserManager(UserManager $fosUserManager)
    {
        $this->fosUserManager = $fosUserManager;
    }

    public function setEntityManager(EntityManagerInterface $doctrine)
    {
        $this->doctrine = $doctrine;
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
                'required' => $this->getSubject()->getId() ? false : true
            ])
            ->add('super_admin', CheckboxType::class, [
                'data' => $this->getSubject()->hasRole(User::ROLE_SUPER_ADMIN),
                'mapped' => false,
                'required' => false,
                'constraints' => new Callback(['callback' => [$this, 'checkSuperAdminSafe']])
            ])
            ->add('groups')
        ;

        if($this->isGranted(User::ROLE_SUPER_ADMIN)){
            $formMapper->getFormBuilder()->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $formEvent){
                $user = $formEvent->getData();

                if($formEvent->getForm()->get('super_admin')->getData() === true){
                    $user->addRole(User::ROLE_SUPER_ADMIN);
                }else{
                    $user->removeRole(User::ROLE_SUPER_ADMIN);
                }
            });
        }else{
            // Only super admin can grant super_admin role
            $formMapper->remove('super_admin');
        }
    }

    /**
     * Validator to check if we are not creating a situation where there would be no super admin left
     *
     * @param $value
     * @param ExecutionContextInterface $context
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function checkSuperAdminSafe($value, ExecutionContextInterface $context)
    {
        if($value !== false || !$this->getSubject()->hasRole(User::ROLE_SUPER_ADMIN)){
            return;
        }

        $dql = "SELECT COUNT(u) FROM ".User::class." u WHERE u.roles LIKE :role AND u.id != :user_id";
        $nbSuperAdmin = $this->doctrine->createQuery($dql)
            ->setParameter(':user_id', $this->getSubject()->getId())
            ->setParameter(':role', '%"'.User::ROLE_SUPER_ADMIN.'"%')
            ->getSingleScalarResult();

        if($nbSuperAdmin === 0){
            $context->buildViolation('no_super_admin_left')
                ->atPath('super_admin')
                ->addViolation();
        }
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
