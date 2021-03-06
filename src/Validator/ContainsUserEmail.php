<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsUserEmail extends Constraint
{

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public $message = 'The email "{{ string }}" already exists.';
}