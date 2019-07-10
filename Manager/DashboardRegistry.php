<?php

namespace Kalamu\CmsAdminBundle\Manager;

use Kalamu\CmsAdminBundle\Manager\DashboardPersistenceInterface;


class DashboardRegistry
{

    protected $storages = [];

    public function registerStorage($alias, DashboardPersistenceInterface $service){
        $this->storages[$alias] = $service;
    }

    public function getStorage($alias){
        return $this->storages[$alias];
    }

}
