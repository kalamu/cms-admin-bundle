<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


class ContrainsEmailsList extends Constraint
{
    public $message = "This field must content only valid email address sparated by semi-colom.";
}