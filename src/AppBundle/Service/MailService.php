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


    /**
     * @param Booking $booking
     * @return int
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendBookingConfirmation(Booking $booking)
    {

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