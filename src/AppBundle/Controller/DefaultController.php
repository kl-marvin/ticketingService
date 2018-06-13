<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ticket;
use AppBundle\Form\BookingTicketsType;
use AppBundle\Form\TicketType;
use AppBundle\Manager\BookingManager;
use AppBundle\Repository\BookingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Stripe\Error\Card;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Booking;
use AppBundle\Form\BookingType;
use Symfony\Component\HttpFoundation\Session\Session;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('Louvre/index.html.twig');
    }


    /**
     * @Route("/order", name="order_step_1")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function orderAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->initializeBooking();

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $bookingManager->generateTicketsForBooking($booking);

            return $this->redirectToRoute("order_step_2");

        }


        return $this->render('Louvre/order.html.twig', array(
            'form' => $form->createView()
        ));

    }

    /**
     * @Route("/information", name="order_step_2")
     */
    public function informationAction(Request $request, BookingManager $bookingManager)
    {

        $booking = $bookingManager->getCurrentBooking();

        $form = $this->createForm(BookingTicketsType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bookingManager->computePrice($booking);
            return $this->redirectToRoute("order_step_3");
        }

        return $this->render('Louvre/information.html.twig', array(
            'form' => $form->createView(),
        ));


    }

    /**
     * @Route("/checkout", name="order_step_3")
     */
    public function checkoutAction(Request $request, BookingManager $bookingManager, \Swift_Mailer $mailer)
    {
        $booking = $bookingManager->getCurrentBooking();
        dump($booking);

        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            $amount = $booking->getTotalPrice();


            \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
            try {
            $stripeReturn = \Stripe\Charge::create(array(
                "amount" => $amount * 100,
                "currency" => "eur",
                "source" => $token,
                "description" => "First test charge!",

            ));
            dump($stripeReturn);
            $bookingManager->computeReference($booking, $stripeReturn['id']);
            $bookingManager->sendEmail($booking, $mailer, $this->renderView('Louvre/emailTemplate.html.twig',
                array('booking' => $booking,)
            ));
            $bookingManager->save($booking);
            $this->addFlash('success', 'Commande effectuée');
            return $this->redirectToRoute("order_step_4");


        }catch(Card $exception)
            {
                $this->addFlash('warning', 'Il y a eu un problème lors de votre paiement. La transaction a été annulée');
            }
        }

        return $this->render('Louvre/checkout.html.twig', array(
            'booking' => $booking,
            'stripe_public_key' => $this->getParameter('stripe_public_key')
        ));
        // Stripe si méthode de paiement ok -> / génération numéro commande /  bdd /  envoie du mail / redirection  // Tableau recap

    }


    /**
     * @Route("/confirmation", name="order_step_4")
     */
    public function confirmationAction(BookingManager $bookingManager)
    {
        $booking = $bookingManager->getCurrentBooking();
        // TODO vider session

        dump($booking);
        return $this->render('Louvre/confirmation.html.twig', array(
            'booking' => $booking,
        ));

    }

}
