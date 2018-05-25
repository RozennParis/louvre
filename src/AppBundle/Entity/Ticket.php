<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
{

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
     * @Assert\NotBlank(groups={"stepTwo"}, payload={"severity"="error"})
     * @Assert\NotNull(groups={"stepTwo"}, payload={"severity"="error"})
     * @Assert\Type("string", groups={"stepTwo"}, payload={"severity"="error"})
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     * @Assert\NotBlank(groups={"stepTwo"}, payload={"severity"="error"})
     * @Assert\NotNull(groups={"stepTwo"}, payload={"severity"="error"})
     * @Assert\Type("string", groups={"stepTwo"}, payload={"severity"="error"})
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var \DateTime
     * @Assert\NotBlank(groups={"stepTwo"}, payload={"severity"="error"})
     * @Assert\NotNull(groups={"stepTwo"}, payload={"severity"="error"})
     * @Assert\Date(groups={"stepTwo"}, payload={"severity"="error"})
     * @Assert\LessThanOrEqual("today", groups={"stepTwo"}, payload={"severity"="error"})
     * @ORM\Column(name="birthDate", type="datetime")
     */
    private $birthDate;

    /**
     * @var \string
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @Assert\Type("bool", groups={"stepTwo"}, payload={"severity"="error"})
     * @ORM\Column(name="reduceRate", type="boolean")
     */
    private $reduceRate = false;

    /**
     * @Assert\Type("integrer")
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @Assert\Type("integer")
     * @ORM\Column(name="age", type="integer")
     */
    private $age;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Booking", inversedBy="tickets")
     */
    private $booking;


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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Ticket
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Ticket
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
     * Set birthDate
     *
     * @param \DateTime $birthDate
     *
     * @return Ticket
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Ticket
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set reduceRate
     *
     * @param boolean $reduceRate
     *
     * @return Ticket
     */
    public function setReduceRate($reduceRate)
    {
        $this->reduceRate = $reduceRate;

        return $this;
    }

    /**
     * Get reduceRate
     *
     * @return boolean
     */
    public function getReduceRate()
    {
        return $this->reduceRate;
    }



    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Ticket
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * Get age
     *
     * @return string
     */
    public function getAge()
    {
        return $this->age;
    }


    /**
     * Set booking
     *
     * @param \AppBundle\Entity\Booking $booking
     *
     * @return Ticket
     */
    public function setBooking(Booking $booking = null)
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * Get booking
     *
     * @return \AppBundle\Entity\Booking
     */
    public function getBooking()
    {
        return $this->booking;
    }


}
