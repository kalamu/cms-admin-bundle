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
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validator to check that each email of a string separated with semi-colom are valid
 */
class ContrainsEmailsListValidator extends ConstraintValidator
{

    public function validate($value, Constraint $constraint){
        $value = (string) $value;

        if(false === strpos($value, ';')){
            $this->validateEmail($value);
        }else{
            $emails = array_map('trim', explode(';', $value));
            foreach($emails as $email){
                $this->validateEmail($email);
            }
        }
    }


    protected function validateEmail($value){
        $valid = filter_var($value, FILTER_VALIDATE_EMAIL);
        if (!$valid) {
            $this->context->addViolation("The email '{{ value }}' is not valid.", array(
                '{{ value }}' => $this->formatValue($value),
            ));
        }
    }
}