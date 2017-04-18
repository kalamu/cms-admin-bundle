<?php

namespace Kalamu\CmsAdminBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


class ContrainsEmailsList extends Constraint
{
    public $message = "Ce champ ne doit contenir que des adresses email valide séparées par des point-virgule.";
}