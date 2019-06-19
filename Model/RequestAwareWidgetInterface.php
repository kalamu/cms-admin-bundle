<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Model;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Interface à implémenter sur un Widget pour avoir la RequestStack injectée
 */
interface RequestAwareWidgetInterface
{

    public function setRequestStack(RequestStack $RequestStack);

}