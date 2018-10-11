<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator as LouvreAssert;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingRepository")
 * @LouvreAssert\SoldOut(groups={"init"})
 * @LouvreAssert\HalfdayBooking(groups={"init"})
 */
class Booking
{
    const TYPE_FULL_DAY = 1;
    const TYPE_HALF_DAY = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255, nullable=true)
     */
    private $firstName;

    /**
     * @var \DateTime
     * @Assert\DateTime()
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime
     * @Assert\DateTime(groups={"init"})
     * @Assert\GreaterThanOrEqual("today", groups={"init"})
     * @LouvreAssert\TuesdaysClosing(groups={"init"})
     * @LouvreAssert\SundaysClosing(groups={"init"})
     * @LouvreAssert\BankHolidays(groups={"init"})
     * @LouvreAssert\OpeningHours(groups={"init"})
     * @ORM\Column(name="visitDate", type="datetime")
     */
    private $visitDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var string
     * @Assert\Email(groups={"init"})
     * @Assert\NotBlank(groups={"init"})
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var int
     * @Assert\Count(min=1, groups={"info"})
     * @Assert\Valid(groups={"info"})
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="booking", cascade={"persist"})
     */
    private $tickets;

    /**
     * @var integer
     *
     * @ORM\Column(name="reference", type="integer")
     */
    private $reference;

    /**
     * @var integer
     */
    private $numberOfTickets;


    /**
     * @var float
     * @ORM\Column(name="totalPrice", type="float")
     */
    private $totalPrice;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Booking
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Booking
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Booking
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set visitDate
     *
     * @param \DateTime $visitDate
     *
     * @return Booking
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Booking
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Booking
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Booking
     */
    public function setReference($reference)
    {
        // $reference = rand(0, 14000);

        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return int
     */
    public function getNumberOfTickets()
    {
        return $this->numberOfTickets;
    }

    /**
     * @param int $numberOfTickets
     */
    public function setNumberOfTickets($numberOfTickets)
    {
        $this->numberOfTickets = $numberOfTickets;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tickets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
    }

    /**
     * Add ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     *
     * @return Booking
     */
    public function addTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets[] = $ticket;
        $ticket->setBooking($this);

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param \AppBundle\Entity\Ticket $ticket
     */
    public function removeTicket(\AppBundle\Entity\Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return Ticket[]| \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Set totalPrice
     *
     * @param float $totalPrice
     *
     * @return Booking
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    /**
     * Get totalPrice
     *
     * @return float
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }
}
