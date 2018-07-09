<?php


namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OpeningHoursValidator extends ConstraintValidator
{

    const CLOSINGTIME = 17;

    public function validate($booking, Constraint $constraint)
    {
        if ($booking ==! null){
            $visitDate = $booking->getVisitDate()->format('Y-m-d');
            $bookingDate = new \DateTime();
            $bookingDate->format('Y-m-d');
            $bookinghour = $bookingDate->format('H');

            if ($bookinghour >= self::CLOSINGTIME && $visitDate === $bookingDate) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }
}