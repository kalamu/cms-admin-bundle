<?php

namespace Kalamu\CmsAdminBundle\Model;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Interface à implémenter sur un Widget pour avoir la RequestStack injectée
 */
interface RequestAwareWidgetInterface
{

    public function setRequestStack(RequestStack $RequestStack);

}