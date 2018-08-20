<?php

namespace AppBundle\Service;

use AppBundle\Entity\Booking;

class MailService
{
    /**
     * @var \Twig_Environment
     */
    private $twig_Environment;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Twig_Environment $twig_Environment, \Swift_Mailer $mailer)
    {
        $this->twig_Environment = $twig_Environment;
        $this->mailer = $mailer;
    }


    public function sendBookingConfirmation(Booking $booking)
    {
        $htlm =  /// ;
        $message = (new \Swift_Message('Confirmation de Commande Numéro : ' . $booking->getReference()))
            ->setFrom('service.billeterie@louvre.com')
            ->setTo($booking->getEmail())
            ->setBody(
                $this->twig_Environment->render('Louvre/emailTemplate.html.twig',
                    array('booking' => $booking)
                    ),
             'text/html'
            );
        return $this->mailer->send($message);
    }
}