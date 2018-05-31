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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BookingManager
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function initializeBooking()
    {
        $booking = new Booking();
        $this->session->set('booking', $booking);


        return $booking;
    }

    public function generateTicketsForBooking(Booking $booking)
    {
        $nbTicket = $booking->getNumberOfTickets();
        while ($nbTicket > count ($booking->getTickets()) ){
            $booking->addTicket(new Ticket());
        }
    }

    public function getCurrentBooking()
    {
        $booking = $this->session->get('booking');

        // lever une exception si pas de booking en session
        return $booking;
    }
}