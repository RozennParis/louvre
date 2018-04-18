<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 29/03/18
 * Time: 17:40
 */

namespace AppBundle\Manager;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\NoBookingException;
use AppBundle\Form\BookingType;
use AppBundle\Form\TicketsType;
use AppBundle\Service\AgeCalculator;
use AppBundle\Service\Tarificator;
use AppBundle\Service\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class BookingManager extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var Tarificator
     */
    private $tarificator;

    /**
     * @var AgeCalculator
     */
    private $ageCalculator;
    /**
     * @var Payment
     */
    private $payment;

    public function __construct(EntityManagerInterface $em, SessionInterface $session, Tarificator $tarificator, AgeCalculator $ageCalculator, Payment $payment)
    {
        $this->entityManager = $em;
        $this->session = $session;
        $this->tarificator = $tarificator;
        $this->ageCalculator = $ageCalculator;
        $this->payment = $payment;
    }

    public function initBooking()
    {
        try {
            $booking = $this->getBookingFromSession();
        } catch (NoBookingException $e) {
            $booking = new Booking();
        }

        return $booking;
    }

    public function completeInit(Booking $booking)
    {
        while (count($booking->getTickets()) !== $booking->getNumberOfTickets())
        {
            if (count($booking->getTickets()) > $booking->getNumberOfTickets()) {
                $booking->removeTicket($booking->getTickets()->last());
            } else {
                $booking->addTicket(new Ticket());
            }
        }
    }


    public function getTicketPrice(Booking $booking)
    {
        $tickets = $booking->getTickets();
        $totalPrice = 0;

        foreach ($tickets as $ticket) {
            $age = $this->ageCalculator->ageCalcul($booking->getVisitDate(), $ticket->getBirthdate());
            $ticket->setAge($age);

            $price = $this->tarificator->priceOfTicket($ticket->getReduceRate(), $ticket->getAge(), $booking->getTypeOfTicket());
            $ticket->setPrice($price);

            $totalPrice += $ticket->getPrice();
        }
        $booking->setTotalPrice($totalPrice);

        return $booking;
    }


    public function getBookingSummary(Request $request, Booking $booking)
    {
        $transactionId = $this->payment->payment($booking, $request->request->get('stripeToken'));
        if (false !== $transactionId) {
            $booking->setTransactionId($transactionId);
            $this->entityManager->persist($booking);
            $this->entityManager->flush();
        }
        return $booking;
    }

    public function getFinalSummary($id)
    {
        $booking = $this->entityManager->getRepository('AppBundle:Booking')->getClientBooking($id);
        return $booking;
    }

    /**
     * @return mixed
     */
    private function getBookingFromSession()
    {
        $booking = $this->session->get('booking'); //gérer le cas où pas de booking

        if (!$booking) {
            throw new NoBookingException();
        }
        return $booking;
    }

    public function clearSession()
    {
        $this->session->clear();
    }

    public function recoverBookingId(Booking $booking)
    {
        $id = $this->session->get('booking')->getId();
        return $id;
    }

    public function setBookingSession(Booking $booking)
    {
        $this->session->set('booking', $booking);
    }

    public function getTicketsFromSession(Booking $booking)
    {
        $tickets = $booking->getTickets();
        return $tickets;
    }
}
