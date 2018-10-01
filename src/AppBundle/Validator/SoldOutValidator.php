<?php


namespace AppBundle\Validator;

use AppBundle\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Entity\Booking;
// use AppBundle\Repository\BookingRepository;

class SoldOutValidator extends ConstraintValidator
{
  const SOLDOUT = 5;

    /**
     * @var BookingRepository
     */
    private $bookingRepository; // A Remplacer par 1 000

    public function __construct(EntityManagerInterface $manager)
    {
        $this->bookingRepository = $manager->getRepository(Booking::class);
    }


    public function validate($booking, Constraint $constraint)
  {
     if(!$booking instanceof Booking)
     {
         return false;
     }

    $numberOfSoldTickets = $this->bookingRepository->countHowManyDailyTicket($booking->getVisitDate());


    if($numberOfSoldTickets + $booking->getNumberOfTickets() > self::SOLDOUT){

        // $message = 'Attention vous ne pouvez plus réserver que .$ticketsLeft. pour ce jour.'
      $this->context->buildViolation($constraint->message)
          ->setParameter('**NB_TICKETS**',self::SOLDOUT - $numberOfSoldTickets)
          ->atPath('numberOfTicket')
          ->addViolation();
    }
  }
}
