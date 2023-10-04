<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LessThanDateHeureDebut extends Constraint
{
    public $message = 'La date limite d\'inscription doit être antérieure à la date de début de la sortie.';
}