<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LessThanDateHeureDebutValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $dateHeureDebut = $this->context->getRoot()->get('dateHeureDebut')->getData();

        if ($dateHeureDebut !== null && $value !== null) {
            if ($dateHeureDebut < $value) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}