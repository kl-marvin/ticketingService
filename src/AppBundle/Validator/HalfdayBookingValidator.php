<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class HalfdayBookingValidator extends ConstraintValidator
{
    public function validate($booking, Constraint $constraint)
    {
        if($booking ==! null){
            $visitDate = $booking->getVisitDate()->format('Y-m-d');
            $bookingDate = new \DateTime();
            $bookingDate->format('Y-m-d');
            $bookingHour = $bookingDate->format('H');
            $type = $booking->getType();

            if ($bookingHour >= 14 && $type == 'Journée' && ($visitDate === $bookingDate))
            {
                $this->context->addViolation($constraint->message);
            }
        }
    }
}


