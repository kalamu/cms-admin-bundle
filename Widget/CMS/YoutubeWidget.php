<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Widget\CMS;

use Kalamu\DashboardBundle\Model\AbstractConfigurableElement;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Form;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * Widget that display a Youtube video
 */
class YoutubeWidget extends AbstractConfigurableElement {

    protected $template;

    public function __construct($template) {
        $this->template = $template;
    }

    public function getTitle() {
        return 'cms.youtube.title';
    }

    public function getDescription() {
        return "cms.youtube.description";
    }

    public function getForm(Form $form) {
        $form
            ->add("url", UrlType::class, array(
                'label' => 'Address of the video',
                'attr' => ['placeholder' => "https://www.youtube.com/watch?v=CODEVIDEO"],
                'sonata_help' => "Copy-past the URL here.",
                'label_attr' => array('class' => 'center-block text-left'),
                'constraints' => new Callback(['callback' => [$this, 'validYoutubeUrl']]),
                ))
            ->add("start_video", TextType::class, [
                'label' => 'Start at',
                'attr' => ["placeholder" => '0:00'],
                'sonata_help' => "For example, '1:29' will start the video at 1m and 29s",
                'constraints' => new Callback(['callback' => [$this, 'validTime']]),
                'required' => false,
            ])
            ->add('ref', ChoiceType::class, [
                'label' => 'Show suggestions',
                'sonata_help' => 'Show the suggestions at the end of the video',
                'choices' => ['Yes' => true, "No" => false],
                'choices_as_values' => true,
                'data' => false
            ])
            ->add("command", ChoiceType::class, [
                'label' => 'Display the controls on the player',
                'choices' => ['Yes' => true, "No" => false],
                'choices_as_values' => true,
                'data' => true
            ])
            ->add("title", ChoiceType::class, [
                'label' => 'Display the video title',
                'choices' => ['Yes' => true, "No" => false],
                'choices_as_values' => true,
                'data' => true
            ])
        ;


        return $form;
    }

    /**
     * @return string
     */
    public function render(TwigEngine $templating, $intention = "edit") {

            $test = $this->parameters;
            $this->parameters['parameters'] =  $test;

        $this->parameters['intention'] = $intention;
        if ($intention === "publish") {
            $baseUrl = 'https://www.youtube-nocookie.com/embed/';
            $baseUrl .= $this->getVideoId($this->parameters['url']);

            $options = [];
            if($this->parameters['start_video']){
                $time = explode(':', $this->parameters['start_video']);
                $options['start'] = ($time[0]*60) + $time[1];
            }
            if(!$this->parameters['ref']){
                $options['rel'] = 0;
            }
            if(!$this->parameters['command']){
                $options['controls'] = 0;
            }
            if(!$this->parameters['title']){
                $options['showinfo'] = 0;
            }

            $this->parameters['url'] = $baseUrl.'?'.http_build_query($options);
        }else{
            $this->parameters['thumbnail'] = "https://img.youtube.com/vi/".$this->getVideoId($this->parameters['url'])."/hqdefault.jpg";
        }

        return $templating->render($this->template, $this->parameters);
    }

    /**
     * Check that the URL of the video is valid
     * @param string $string
     * @param ExecutionContextInterface $context
     */
    public function validYoutubeUrl($string, ExecutionContextInterface $context){
        try{
            $this->getVideoId($string);
        } catch (\InvalidArgumentException $ex) {
            $context->addViolation($ex->getMessage());
        }
    }

    /**
     * Check the format for start_at argument
     * @param string $string
     * @param ExecutionContextInterface $context
     */
    public function validTime($string, ExecutionContextInterface $context){
        if(!$string){
            return;
        }
        if(!preg_match('/\d{1,2}\:\d{2}/', $string)){
            $context->addViolation("The format is invalid. It must be 'mm:ss'");
            return ;
        }

        $time = explode(':', $string);
        if($time[1] >=60){
            $context->addViolation("The format is invalid. It must be 'mm:ss'");
            return ;
        }
    }

    /**
     * Parse the URL to get the video ID
     * @param string $url
     * @return string
     * @throws \InvalidArgumentException
     */
    protected function getVideoId($url){
        parse_str(parse_url($url, PHP_URL_QUERY), $originParams);
        if(!isset($originParams['v'])){
            throw new \InvalidArgumentException("The URL is not recognized as a valid video URL.");
        }
        return $originParams['v'];
    }
}
