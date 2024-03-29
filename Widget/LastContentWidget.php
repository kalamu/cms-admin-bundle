<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Widget;

use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Kalamu\CmsCoreBundle\ContentType\Interfaces\PublishStatusInterface;
use Kalamu\CmsCoreBundle\Manager\ContentTypeManager;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraints\Range;
/**
 * Widget that show the last published contents
 */
class LastContentWidget extends AbstractConfigurableElement
{
    /**
     * @var \Kalamu\CmsAdminBundle\Manager\ContentTypeManager
     */
    protected $manager;

    protected $template;

    public function __construct(ContentTypeManager $manager, $template){
        $this->manager = $manager;
        $this->template = $template;
    }

    public function getTitle() {
        return 'cms.last_content.title';
    }

    public function getDescription() {
        return 'cms.last_content.description';
    }

    public function getForm(Form $form){
        $form->add("type", ChoiceType::class, array(
            'choices' => array_flip($this->manager->getLabels()),
            'choices_as_values' => true,
            'label' => 'Content type',
        ));
        $form->add("max", IntegerType::class, array(
            'constraints' => array(
                new Range(array(
                    'min'   => 1,
                    'max'   => 30
                ))
            ),
            'label' => 'Number of element to display',
            'data'  => 10
        ));

        return $form;
    }

    /**
     * @return string
     */
    public function render(TwigEngine $templating){
        $typeManager = $this->manager->getType($this->parameters['type']);

        $baseQuery = $typeManager->getBasePublicQuery();
        if($typeManager->getReflectionClass()->implementsInterface(PublishStatusInterface::class)){
            $baseQuery->orderBy('c.published_at', 'DESC');
        }

        $contents = $baseQuery->setMaxResults($this->parameters['max'])
                ->getQuery()
                ->getResult();

        return $templating->render($this->template, array('contents' => $contents, 'manager' => $typeManager));
    }

}