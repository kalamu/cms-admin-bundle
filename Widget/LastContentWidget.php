<?php

namespace Kalamu\CmsAdminBundle\Widget;

use Kalamu\CmsAdminBundle\Manager\ContentTypeManager;
use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraints\Range;
/**
 * Widget affichant les derniers contenus publiés
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
            'label' => 'Type de contenu',
        ));
        $form->add("max", IntegerType::class, array(
            'constraints' => array(
                new Range(array(
                    'min'   => 1,
                    'max'   => 30
                ))
            ),
            'label' => 'Nombre à afficher',
            'data'  => 10
        ));

        return $form;
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating){
        $typeManager = $this->manager->getType($this->parameters['type']);

        $baseQuery = $typeManager->getBasePublicQuery();
        if($typeManager->getReflectionClass()->implementsInterface('Kalamu\CmsAdminBundle\ContentType\Interfaces\PublishStatusInterface')){
            $baseQuery->orderBy('c.published_at', 'DESC');
        }

        $contenus = $baseQuery->setMaxResults($this->parameters['max'])
                ->getQuery()
                ->getResult();

        return $templating->render($this->template, array('contenus' => $contenus, 'manager' => $typeManager));
    }

}