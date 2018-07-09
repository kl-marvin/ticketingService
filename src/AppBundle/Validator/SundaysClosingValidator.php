<?php

namespace AppBundle\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SundaysClosingValidator extends ConstraintValidator
{
    const SUNDAYS = 0;

    public function validate($date, Constraint $constraint)
    {
        if($date !== null){
            $closedDay = $date->format('w');

            if ($closedDay == self::SUNDAYS){
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }
}