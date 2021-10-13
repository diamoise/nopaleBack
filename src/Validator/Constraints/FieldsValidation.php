<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FieldsValidation extends Constraint
{
    public string $message = "Désolé, ce champs ne peut pas etre vide";

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return \get_class($this).'validator';
    }
}
