<?php

namespace Kalamu\CmsAdminBundle\Widget\CMS;

use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;

/**
 * Widget pour afficher des Vidéo ou autre comptenu
 */
class EmbedWidget extends AbstractConfigurableElement {

    protected $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function getTitle() {
        return 'Vidéo';
    }

    public function getDescription() {
        return "Saisissez l'url de votre video Youtube";
    }

    public function getForm(Form $form) {
        $form
                ->add("url", TextType::class, array(
                    'label' => 'Url de la video',
                    'attr' => ['placeholder' => "https://www.youtube.com/watch?v=CODEVIDEO"],
                    'sonata_field_description' => "Copiez-collez l'url de la video ici.",
                    'label_attr' => array('class' => 'center-block text-left')))
                ->add("autoplay", ChoiceType::class, [
                    'label' => 'lancer la video automatiquement',
                    'sonata_field_description' => "Déconseillé.",
                    'choices' => ['Oui' => true, "non" => false],
                    'choices_as_values' => true,])
                ->add("widht", IntegerType::class, ['label' => 'Largeur de la video', 'data' => 560])
                ->add("height", IntegerType::class, ['label' => 'Hauteur de la video', 'data' => 315])
                ->add("start_video", TextType::class, [
                    'label' => 'Démarrer à',
                    'attr' => ["placeholder" => '0:00'],
                    'sonata_field_description' => "Par exemple 1:29  pour démarrer la video à 1m29s",
                ])
        ;


        return $form;
    }

    /**
     * Génère le widget qui doit être affiché dans le tableau de bord
     * @return string
     */
    public function render(TwigEngine $templating, $intention = "edit") {

        $this->parameters['intention'] = $intention;
        if ($intention === "publish") {
            $this->parameters['url'] = str_replace("watch?v=", "embed/", $this->parameters['url']);
            $this->parameters['start_video'] = ($this->parameters['start_video'] > 0) ? $this->parameters['start_video'] : "0:00";
            // Commencement de la video en minute et second
            $starting_ms = explode(":", $this->parameters['start_video']);

            // Convetion en seconde des minute + les escodne
            
            $this->parameters['start_video'] = $starting_ms[0] * 60 + $starting_ms[1];

            $urlParams = "?rel=0&amp;controls=1&amp;showinfo=0";
            $urlParams = $urlParams . "&amp;start=" . $this->parameters['start_video'];
            $urlParams = $urlParams . "&amp;autoplay=" . $this->parameters['autoplay'];

            $this->parameters['url'] = $this->parameters['url'] . $urlParams;
        }else{
            $codeUrl = explode("=", $this->parameters['url']);
            $this->parameters['thumbnail'] = "https://img.youtube.com/vi/".end($codeUrl)."/hqdefault.jpg";
        }

        return $templating->render($this->template, $this->parameters);
    }

}
