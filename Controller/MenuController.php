<?php

namespace Kalamu\CmsAdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class MenuController extends CRUDController
{

    public function render($view, array $parameters = array(), Response $response = null) {
        if(in_array($parameters['action'], ['create', 'edit'])){
            $parameters['items_pickers_manager'] = $this->get('kalamu_cms_admin.menu_item.manager');
        }

        return parent::render($view, $parameters, $response);
    }

    /**
     * Methode utilisé pour explorer les contenus disponibles pour la construction d'un menu
     * @param Request $request
     * @param type $type
     */
    public function exploreContentAction(Request $request, $name){
        $infos = $this->get('kalamu_cms_admin.menu_item.manager')->getItems($name,
                $request->query->get('page', 1),
                10,
                $request->query->get('context', null),
                $request->query->get('search', ''));

        $response = new Response(json_encode($infos, JSON_PRETTY_PRINT));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Méthode utilisée pour récupérer les infos d'un contenu pour la construction d'un menu
     * @param Request $request
     * @param string $type
     * @param int $id
     */
    public function getContentAction(Request $request, $name, $id){
        $infos = $this->get('kalamu_cms_admin.menu_item.manager')->getItem($name, $id, $request->query->get('context', null));

        $response = new Response(json_encode($infos, JSON_PRETTY_PRINT));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}