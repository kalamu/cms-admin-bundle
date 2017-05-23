<?php

namespace Kalamu\CmsAdminBundle\Widget;

use Kalamu\CmsAdminBundle\Model\RequestAwareWidgetInterface;
use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints;

/**
 * Widget affichant un formulaire de contact
 */
class FormContactWidget extends AbstractConfigurableElement implements RequestAwareWidgetInterface
{

    /**
     * @var FormFactory
     */
    protected $formFactory;

    protected $template;

    /**
     * @var RequestStack
     */
    protected $request_stack;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    public function __construct(FormFactory $formFactory, $template, \Swift_Mailer $mailer){
        $this->formFactory = $formFactory;
        $this->template = $template;
        $this->mailer = $mailer;
    }

    public function getTitle() {
        return 'cms.form_contact.title';
    }

    public function getDescription() {
        return 'cms.form_contact.description';
    }

    public function getForm(Form $form){
        $form->add('selectable_destinataire', ChoiceType::class, array(
            'label' => 'Destinataire sélectionnable',
            'choices'   => array('Oui' => true, 'Non' => false),
            'choices_as_values' => true,
            'data'  => false,
            'sonata_help'    => "Permettre à l'utilisateur de sélectionner le destinataire du message"
        ));

        $form->add("destinataire_simple", CollectionType::class, array(
            'type' => EmailType::class,
            'options' => array(
                'required' => true,
                'constraints' => array(
                    new Constraints\NotBlank(),
                    new Constraints\Email()
                ),
            ),
            'allow_add' => true,
            'allow_delete' => true,
            'label' => 'Adresses de destination',
            'sonata_help'    => "Adresse de destination pour les mails qui seront envoyés depuis ce formulaire."
        ));
        $form->add("label_choix_destinataire", TextType::class, array(
            'label' => 'Label du sélecteur de destinataire',
            'data'  => "Sélectionnez la personne que vous souhaitez contacter",
            'required' => true));
        $form->add("choix_destinataire", CollectionType::class, array(
            'type' => 'cms_contact_form_contact',
            'label' => 'Liste des destinataires',
            'allow_add' => true,
            'allow_delete' => true));


        $form->add("success", TextType::class, array(
            'label' => 'Message en cas de succès de l\'envoie',
            'data'  => "Votre message a bien été envoyé.",
            'required' => true,
            'constraints' => array(
                new Constraints\NotBlank(),
            )
        ));
        $form->add("error", TextType::class, array(
            'label' => "Message en cas d'erreur de l'envoie",
            'data'  => "Nous sommes désolés, un problème technique est survenue et votre mail n'a pu être envoyé.",
            'required' => true,
            'constraints' => array(
                new Constraints\NotBlank(),
            )
        ));

        return $form;
    }

    public function setRequestStack(RequestStack $RequestStack) {
        $this->request_stack = $RequestStack;
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating, $intention = 'publish'){

        if('publish' == $intention){
            $form = $this->createFormContact();
            $form->handleRequest($this->request_stack->getMasterRequest());
            $status = null;
            $message = null;
            if($form->isValid()){
                $mail = $form->getData();

                $message = \Swift_Message::newInstance();
                $message->setSubject( $mail['objet'] );
                $message->setFrom( $mail['email'], $mail['nom']);

                $body = <<<EOL
Le message suivant a été envoyé depuis le formulaire de contact du site.
Nom prénom : {$mail['nom']}
Email: {$mail['email']}


{$mail['message']}
EOL;
                $message->setBody($body);

                if($this->parameters['selectable_destinataire']){
                    $emails = $this->parameters['choix_destinataire'][$mail['destinataire']]['emails'];
                    $destinataires = explode(';', $emails);
                }else{
                    $destinataires = $this->parameters['destinataire_simple'];
                }
                foreach($destinataires as $destinataire){
                    $message->addTo($destinataire);
                }

                $status = $this->mailer->send($message) ? 'success' : 'error';
                $message = $this->parameters[$status];
                if($status == 'success'){
                    $form = $this->createFormContact();
                }
            }

            return $templating->render($this->template, array('form' => $form->createView(), 'status' => $status, 'message' => $message));
        }else{
            return $templating->render('KalamuCmsAdminBundle:Widget:form_contact_admin.html.twig', array('parameters' => $this->parameters));
        }
    }

    public function renderConfigForm(TwigEngine $templating, Form $form){
        return $templating->render('KalamuCmsAdminBundle:Form/Widget:form_contact.html.twig', array('form' => $form->createView(), 'widget' => $this));
    }

    /**
     * Crée le formulaire de contact
     * @return type
     */
    protected function createFormContact(){
        $form = $this->formFactory->createBuilder();

        $form->add("nom", 'text', array(
            'label' => 'Nom prénom',
            'required' => true,
            'constraints' => array(
                new Constraints\NotBlank(),
                new Constraints\Length(array('min' => 2, 'max' => 200))
            )
        ));
        $form->add("email", 'email', array(
            'label' => 'Adresse Email',
            'required' => true,
            'constraints' => array(
                new Constraints\NotBlank(),
                new Constraints\Email()
            )
        ));

        if($this->parameters['selectable_destinataire']){

            $choice_list = array();
            foreach($this->parameters['choix_destinataire'] as $i => $choix){
                $choice_list[$choix['label']] = $i;
            }

            $form->add('destinataire', 'choice', array(
                'choices' => $choice_list,
                'choices_as_values' => true,
                'expanded'  => true,
                'required'  => true,
                'label' => $this->parameters['label_choix_destinataire']
            ));

        }

        $form->add("objet", 'text', array(
            'label' => 'Objet',
            'required' => true,
            'constraints' => array(
                new Constraints\NotBlank(),
                new Constraints\Length(array('min' => 5, 'max' => 200))
            )
        ));
        $form->add("message", 'textarea', array(
            'label' => 'Message',
            'required' => true,
            'constraints' => array(
                new Constraints\NotBlank(),
                new Constraints\Length(array('min' => 10))
            )
        ));
        $form->add("submit", 'submit', array(
            'label' => 'Envoyer',
        ));

        return $form->getForm();
    }
}