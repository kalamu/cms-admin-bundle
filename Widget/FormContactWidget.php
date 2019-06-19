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

use Kalamu\CmsAdminBundle\Form\Type\FormContactType;
use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints;

/**
 * Widget that show a form contact
 */
class FormContactWidget extends AbstractConfigurableElement
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

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(FormFactory $formFactory, $template, \Swift_Mailer $mailer, EventDispatcherInterface $eventDispatcher){
        $this->formFactory = $formFactory;
        $this->template = $template;
        $this->mailer = $mailer;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getTitle() {
        return 'cms.form_contact.title';
    }

    public function getDescription() {
        return 'cms.form_contact.description';
    }

    public function getForm(Form $form){
        return FormContactType::class;
    }

    public function setRequestStack(RequestStack $RequestStack) {
        $this->request_stack = $RequestStack;
    }

    /**
     * @return string
     */
    public function render(TwigEngine $templating, $intention = 'publish'){

        if('publish' == $intention){
            $form = $this->createFormContact();
            $form->handleRequest($this->request_stack->getMasterRequest());
            $status = null;
            $message = null;
            if($form->isValid()){
                $datas = $form->getData();

                $mail = \Swift_Message::newInstance();

                if($this->parameters['selectable_recipient']){
                    $recipients = $this->parameters['recipient_choice'][$datas['recipient']]['emails'];
                    $recipients = explode(';', $recipients);
                }else{
                    $recipients = $this->parameters['simple_recipient'];
                }
                foreach($recipients as $recipient){
                    $mail->addTo($recipient);
                }

                $mail->setSubject( $datas['objet'] );
                $mail->setFrom( current($recipients), $datas['nom']);
                $mail->setReplyTo($datas['email']);

                $body = <<<EOL
The following message has been sent from the contact form of the website.
Name : {$datas['nom']}
Email : {$datas['email']}

{$datas['message']}
EOL;
                $mail->setBody($body);


                $status = $this->mailer->send($mail) ? 'success' : 'error';
                $message = $this->parameters[$status];
                if($status === 'success'){
                    $form = $this->createFormContact();
                    $this->eventDispatcher->dispatch('widget.form_contact.send');
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
     * Create the form
     * @return type
     */
    protected function createFormContact(){
        $form = $this->formFactory->createBuilder();

        $form->add("nom", 'text', array(
            'label' => 'Name',
            'required' => true,
            'constraints' => array(
                new Constraints\NotBlank(),
                new Constraints\Length(array('min' => 2, 'max' => 200))
            )
        ));
        $form->add("email", 'email', array(
            'label' => 'Email address',
            'required' => true,
            'constraints' => array(
                new Constraints\NotBlank(),
                new Constraints\Email()
            )
        ));

        if($this->parameters['selectable_recipient']){

            $choice_list = array();
            foreach($this->parameters['recipient_choice'] as $i => $choix){
                $choice_list[$choix['label']] = $i;
            }

            $form->add('recipient', 'choice', array(
                'choices' => $choice_list,
                'choices_as_values' => true,
                'expanded'  => false,
                'required'  => true,
                'label' => $this->parameters['recipient_choice_label'],
                'placeholder' => '...'
            ));

        }

        $form->add("objet", 'text', array(
            'label' => 'Object',
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

        return $form->getForm();
    }
}