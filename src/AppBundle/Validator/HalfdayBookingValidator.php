<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Entity\Booking;




class HalfdayBookingValidator extends ConstraintValidator
{

  const HALFDAY = 14;

    public function validate($booking, Constraint $constraint)
    {
        if($booking !== null){
            $visitDate = $booking->getVisitDate()->format('Y-m-d');
            $currentDate = new \DateTime();
            $bookingDate = $currentDate->format('Y-m-d');
            $bookingHour = $currentDate->format('H');
            $type = $booking->getType();

            if ($bookingHour >= self::HALFDAY && $type == Booking::TYPE_FULL_DAY && ($visitDate === $bookingDate))
            {
                $this->context->buildViolation($constraint->message)->atPath('type')->addViolation();
                    // Attacher l'erreur au formulaire : $this->context->addViolation($constraint->message);
            }
        }
    }
}
