<?php

namespace App\Validator\Constraints;

use http\Exception\UnexpectedValueException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class isFieldsValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            $this->context->buildViolation($constraint->message)
                 ->addViolation()
                ;
        }

        if (!is_string($value)) {
            throw new  UnexpectedValueException($value, 'string');
        }
    }
}
