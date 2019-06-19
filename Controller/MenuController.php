<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MenuController extends CRUDController
{

    public function renderWithExtraParams($view, array $parameters = [], Response $response = null)
    {
        if(in_array($parameters['action'], ['create', 'edit'])){
            $parameters['items_pickers_manager'] = $this->get('kalamu_cms_admin.menu_item.manager');
        }

        return parent::renderWithExtraParams($view, $parameters, $response);
    }

    /**
     * This action is used to browse contents in content selectors (for menu or
     * link picker)
     *
     * @param Request $request
     * @param type $name
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
     * This action return datas about one content
     *
     * @param Request $request
     * @param string $name
     * @param int $id
     */
    public function getContentAction(Request $request, $name, $id){
        $infos = $this->get('kalamu_cms_admin.menu_item.manager')->getItem($name, $id, $request->query->get('context', null));

        $response = new Response(json_encode($infos, JSON_PRETTY_PRINT));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}