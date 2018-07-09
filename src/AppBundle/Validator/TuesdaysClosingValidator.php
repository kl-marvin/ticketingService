<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TuesdaysClosingValidator extends ConstraintValidator
{
    const TUESDAYS = 2;

    public function validate($date, Constraint $constraint)
    {
        if($date !== null){
            $closedDay = $date->format('w');

            if ($closedDay == self::TUESDAYS){
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }

}