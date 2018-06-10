<?php

namespace AppBundle\Entity;

use AppBundle\Validator\Constraints as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Booking
 *
 * @ORM\Table(name="booking")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookingRepository")
 * @AppAssert\NotMoreThousand(groups={"stepOne"})
 * @AppAssert\TooLateForToday(groups={"stepOne"}, payload={"severity"="error"})
 * @AppAssert\HalfDay(groups={"stepOne"}, payload={"severity"="error"})
 */
class Booking
{
    const TYPE_OF_TICKET_DAY = true;
    const TYPE_OF_TICKET_HALFDAY = false;
    const NB_TICKET_MAX = 10;
    const NB_TICKET_MIN = 1;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @Assert\Date(message="validator.date.message")
     * @ORM\Column(name="bookingDate", type="datetime")
     */
    private $bookingDate;

    /**
     * @var string
     * @Assert\Email(groups={"stepOne"})
     * @Assert\NotBlank(groups={"stepOne"})
     * @Assert\NotNull(groups={"stepOne"})
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     * @Assert\Type("bool", groups={"stepOne"})
     * @ORM\Column(name="typeOfTicket", type="boolean")
     */
    private $typeOfTicket = self::TYPE_OF_TICKET_DAY;

    /**
     * @var \DateTime $visitDate
     * @Assert\NotBlank(groups={"stepOne"}, payload={"severity"="error"})
     * @Assert\NotNull(groups={"stepOne"}, payload={"severity"="error"})
     * @Assert\Date(groups={"stepOne"}, payload={"severity"="error"})
     * @Assert\GreaterThanOrEqual("today", groups={"stepOne"}, payload={"severity"="error"})
     * @AppAssert\NotTuesday(groups={"stepOne"}, payload={"severity"="error"})
     * @AppAssert\NotSunday(groups={"stepOne"}, payload={"severity"="error"})
     * @AppAssert\NotHolidays(groups={"stepOne"}, payload={"severity"="error"})
     * @ORM\Column(name="visitDate", type="datetime")
     */
    private $visitDate;

    /**
     * @var int
     * @Assert\NotBlank(groups={"stepOne"}, payload={"severity"="error"})
     * @Assert\NotNull(groups={"stepOne"}, payload={"severity"="error"})
     * @Assert\Type("integer", groups={"stepOne"}, payload={"severity"="error"})
     * @ORM\Column(name="numberOfTickets", type="integer")
     */
    private $numberOfTickets;

    /**
     * @var int
     * @Assert\Type("integer", payload={"severity"="error"})
     * @ORM\Column(name="totalPrice", type="integer")
     */
    private $totalPrice;

    /**
     * @Assert\Valid(groups={"stepTwo"}, payload={"severity"="error"})
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Ticket", mappedBy="booking", cascade={"persist"})
     */
    private $tickets;

    /**
     * @var string
     * @ORM\Column(name="transactionId", type="string")
     */
    private $transactionId;

    /**
     * Booking constructor.
     */
    public function __construct()
    {
        $this->bookingDate = new \DateTime();
        $this->tickets = new ArrayCollection();
    }

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
     * Set bookingDate
     *
     * @param \DateTime $bookingDate
     *
     * @return Booking
     */
    public function setBookingDate($bookingDate)
    {
        $this->bookingDate = $bookingDate;

        return $this;
    }

    /**
     * Get bookingDate
     *
     * @return \DateTime
     */
    public function getBookingDate()
    {
        return $this->bookingDate;
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
     * Set typeOfTicket
     *
     * @param string $typeOfTicket
     *
     * @return Booking
     */
    public function setTypeOfTicket($typeOfTicket)
    {
        $this->typeOfTicket = $typeOfTicket;

        return $this;
    }

    /**
     * Get typeOfTicket
     *
     * @return string
     */
    public function getTypeOfTicket()
    {
        return $this->typeOfTicket;
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
     * Set numberOfTickets
     *
     * @param integer $numberOfTickets
     *
     * @return Booking
     */
    public function setNumberOfTickets($numberOfTickets)
    {
        $this->numberOfTickets = $numberOfTickets;

        return $this;
    }

    /**
     * Get numberOfTickets
     *
     * @return int
     */
    public function getNumberOfTickets()
    {
        return $this->numberOfTickets;
    }

    /**
     * Set totalPrice
     *
     * @param integer $totalPrice
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
     * @return int
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * Add ticket
     * @param Ticket $ticket
     * @return Booking
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;
        $ticket->setBooking($this);
        return $this;
    }

    /**
     * Remove ticket
     * @param Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Set transactionId
     *
     * @param string $transactionId
     *
     * @return Booking
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

}
