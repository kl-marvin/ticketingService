<?php
/**
 * Created by PhpStorm.
 * User: Marvin
 * Date: 30/05/2018
 * Time: 11:10
 */

namespace AppBundle\Manager;


use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Service\MailService;
use AppBundle\Service\PaiementService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BookingManager
{
    /**
     *
     */
    const PRICE_ADULT = 16;
    /**
     *
     */
    const FREE = 0;
    /**
     *
     */
    const CHILD_PRICE = 8;
    /**
     *
     */
    const SENIOR_PRICE = 12;
    /**
     *
     */
    const AGE_CHILD = 4;
    /**
     *
     */
    const AGE_ADULT = 12;
    /**
     *
     */
    const AGE_SENIOR = 60;


    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PaiementService
     */
    private $paiementService;
    /**
     * @var MailService
     */
    private $mailService;

    /**
     * BookingManager constructor.
     * @param SessionInterface $session
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager, PaiementService $paiementService, MailService $mailService)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->paiementService = $paiementService;
        $this->mailService = $mailService;
    }

    /**
     * @return Booking
     */
    public function initializeBooking()
    {
        $booking = new Booking();
        $this->session->set('booking', $booking);


        return $booking;
    }

    /**
     * @param Booking $booking
     */
    public function generateTicketsForBooking(Booking $booking)
    {
        $nbTicket = $booking->getNumberOfTickets();
        while ($nbTicket > count($booking->getTickets())) {
            $booking->addTicket(new Ticket());
        }
    }

    /**
     * @return Booking
     */
    public function getCurrentBooking()
    {
        $booking = $this->session->get('booking');

        // lever une exception si pas de booking en session
        return $booking;
    }

    /**
     * @param Booking $booking
     * @return Booking
     */
    public function computePrice(Booking $booking)
    {
        $totalPrice = 0;
        foreach ($booking->getTickets() as $ticket) {
            $age = $ticket->getAge();


            if ($age < self::AGE_CHILD) {
                $price = self::FREE;
            } elseif ($age < self::AGE_ADULT) {
                $price = self::CHILD_PRICE;
            } elseif ($age > self::AGE_SENIOR) {
                $price = self::SENIOR_PRICE;
            } else {
                $price = self::PRICE_ADULT;
            }

            if ($ticket->getReducedPrice() && $price > 10) {
                $price = 10;
            }

            if ($booking->getType() == Booking::TYPE_HALF_DAY) {
                $price *= 0.5;
            }

            $totalPrice += $price;
            $ticket->setPrice($price);

        }

        return $booking->setTotalPrice($totalPrice);
    }

    /**
     * @param Booking $booking
     * @return Booking
     */
    public function computeReference(Booking $booking, $reference)
    {
        $ref = $booking->setReference($reference);

        return $ref;

    }


    /**
     * @param Booking $booking
     * @param \Swift_Mailer $mailer
     */
    public function sendEmail(Booking $booking, \Swift_Mailer $mailer, $html)
    {



    }

    public function save($booking)
    {
        $this->entityManager->persist($booking);
        $this->entityManager->flush();
    }

    public function paiement(Booking $booking)
    {
        $reference = $this->paiementService->bookingCheckout($booking);
        if($reference !== false){
            $booking->setReference($reference);
            $this->mailService->sendBookingConfirmation($booking);
            //$this->$this->save($booking);
            return true;
        }
        return false;

    }


}