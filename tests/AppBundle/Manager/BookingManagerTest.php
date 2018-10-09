<?php

namespace tests\AppBundle\Manager;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Manager\BookingManager;
use AppBundle\Service\MailService;
use AppBundle\Service\PaiementService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingManagerTest extends TestCase
{
    /** @var BookingManager */
    private $bookingManager;


    /**
     * @param $visitDate
     * @param $birthDate
     * @param $isReduce
     * @param $expectedPrice
     *
     * @dataProvider bookingDataProvider()
     */
    public function testComputePrice($visitDate,$type, $birthDate, $isReduce, $expectedPrice)
    {
        $booking = new Booking();

        $booking->setVisitDate(new \DateTime($visitDate));
        $booking->setType($type);


        $ticket = new Ticket();
        $ticket->setBirthDate(new \DateTime($birthDate));
        $ticket->setReducedPrice($isReduce);

        $booking->addTicket($ticket);


        $this->assertEquals($expectedPrice, $this->bookingManager->computePrice($booking)->getTotalPrice());
    }


    public function bookingDataProvider()
    {
        return [
            ['2018-10-25',Booking::TYPE_FULL_DAY,'1994-01-18',false, BookingManager::PRICE_ADULT],
            ['2018-10-25',Booking::TYPE_FULL_DAY,'1994-01-18',true, BookingManager::PRICE_REDUCED],
            ['2018-10-25',Booking::TYPE_HALF_DAY,'1994-01-18',false, BookingManager::PRICE_ADULT*BookingManager::REDUCE_COEFF],
            ['2018-10-25',Booking::TYPE_HALF_DAY,'1994-01-18',true, BookingManager::PRICE_REDUCED*BookingManager::REDUCE_COEFF],

            ['2018-10-25',Booking::TYPE_FULL_DAY,'1957-01-18',false, BookingManager::SENIOR_PRICE],
            ['2018-10-25',Booking::TYPE_FULL_DAY,'2007-01-18',false, BookingManager::CHILD_PRICE],
            ['2018-10-25',Booking::TYPE_FULL_DAY,'2016-01-18',false, BookingManager::FREE],


        ];
    }

    public function setUp()
    {
        $session = $this->getMockBuilder(SessionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $em = $this->getMockBuilder(EntityManagerInterface::class)->disableOriginalConstructor()->getMock();
        $paiementService = $this->getMockBuilder(PaiementService::class)->disableOriginalConstructor()->getMock();
        $mailService = $this->getMockBuilder(MailService::class)->disableOriginalConstructor()->getMock();
        $validator = $this->getMockBuilder(ValidatorInterface::class)->disableOriginalConstructor()->getMock();


        $this->bookingManager  = new BookingManager($session,$em,$paiementService,$mailService,$validator);
    }

}