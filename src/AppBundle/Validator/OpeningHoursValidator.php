<?php


namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OpeningHoursValidator extends ConstraintValidator
{

    const CLOSINGTIME = 17;

    public function validate($date, Constraint $constraint)
    {
        if ($date ==! null){
            $visitDate = $date->format('Y-m-d');
            $now = new \DateTime();
            $bookingDate = $now->format('Y-m-d');
            $bookinghour = $now->format('H');

            if ($bookinghour >= self::CLOSINGTIME && $visitDate === $bookingDate) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }
}
