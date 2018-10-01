<?php

namespace AppBundle\Controller;

use AppBundle\Exception\InvalidCurrentBooking;
use AppBundle\Exception\NoCurrentBookingException;
use AppBundle\Form\BookingTicketsType;
use AppBundle\Form\BookingType;
use AppBundle\Manager\BookingManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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
     *
     * @throws InvalidCurrentBooking
     * @throws NoCurrentBookingException
     */
    public function informationAction(Request $request, BookingManager $bookingManager)
    {

        $booking = $bookingManager->getCurrentBooking(['init']);

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
     * @throws InvalidCurrentBooking
     * @throws NoCurrentBookingException
     */
    public function checkoutAction(BookingManager $bookingManager)
    {
        $booking = $bookingManager->getCurrentBooking(['init', 'info']);

        if ($bookingManager->paiement($booking)) {
            $this->addFlash('success', 'Commande validée !');
            return $this->redirectToRoute("order_step_4");
        }

        return $this->render('Louvre/checkout.html.twig', array(
            'booking' => $booking,
            'stripe_public_key' => $this->getParameter('stripe_public_key')
        ));

    }


    /**
     * @Route("/confirmation", name="order_step_4")
     * @throws InvalidCurrentBooking
     * @throws NoCurrentBookingException
     */
    public function confirmationAction(BookingManager $bookingManager)
    {
        $booking = $bookingManager->getCurrentBooking(['init', 'info']);
        $bookingManager->removeCurrentBooking();

        return $this->render('Louvre/confirmation.html.twig', array(
            'booking' => $booking,
        ));
    }


}
