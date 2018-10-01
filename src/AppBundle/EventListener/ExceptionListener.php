<?php


namespace AppBundle\EventListener;


use AppBundle\Exception\InvalidCurrentBooking;
use AppBundle\Exception\NoCurrentBookingException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Routing\RouterInterface;


class ExceptionListener
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(RouterInterface $router, FlashBagInterface $flashBag)
    {

        $this->router = $router;
        $this->flashBag = $flashBag;
    }
    
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();


        if ($exception instanceof NoCurrentBookingException || $exception instanceof InvalidCurrentBooking) {
            $response = new RedirectResponse($this->router->generate('homepage'));
            $this->flashBag->add('warning', 'Erreur recup commande');
            $event->setResponse($response);

        }
    }
}