<?php

namespace AppBundle\Service;

use AppBundle\Entity\Booking;
use Stripe\Error\Card;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PaiementService
{

    private $stripeSecretKey;
    /**
     * @var Request
     */
    private $request;

    public function __construct($stripeSecretKey, RequestStack $requestStack)
    {

        $this->stripeSecretKey = $stripeSecretKey;
        $this->request = $requestStack->getCurrentRequest();

    }

    public function bookingCheckout(Booking $booking)
    {

        if ($this->request->isMethod('POST')) {
            $token = $this->request->request->get('stripeToken');
            $amount = $booking->getTotalPrice();


            \Stripe\Stripe::setApiKey($this->stripeSecretKey);
            try {
                $stripeReturn = \Stripe\Charge::create(array(
                    "amount" => $amount * 100,
                    "currency" => "eur",
                    "source" => $token,
                    "description" => "First test charge!",

                ));


                return $stripeReturn['id'];

            }catch(Card $exception)
            {
                //TODO
                // lever une exception

                // $this->addFlash('warning', 'Il y a eu un problème lors de votre paiement. La transaction a été annulée');
                return false;
            }
        }


        return false;

//

    }
}
