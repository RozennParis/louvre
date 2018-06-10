<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Exception\NoBookingException;
use AppBundle\Service\AgeCalculator;
use AppBundle\Service\MailerService;
use AppBundle\Service\Tarificator;
use AppBundle\Service\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class BookingManager
 * @package AppBundle\Manager
 */
class BookingManager
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

    /**
     * @var MailerService
     */
    private $mailerService;


    /**
     * BookingManager constructor.
     * @param EntityManagerInterface $em
     * @param SessionInterface $session
     * @param Tarificator $tarificator
     * @param AgeCalculator $ageCalculator
     * @param Payment $payment
     * @param MailerService $mailerService
     */
    public function __construct(EntityManagerInterface $em, SessionInterface $session, Tarificator $tarificator, AgeCalculator $ageCalculator, Payment $payment, MailerService $mailerService)
    {
        $this->entityManager = $em;
        $this->session = $session;
        $this->tarificator = $tarificator;
        $this->ageCalculator = $ageCalculator;
        $this->payment = $payment;
        $this->mailerService = $mailerService;
    }

    /**
     * @return Booking|mixed
     */
    public function initBooking()
    {
        try {
            $booking = $this->getCurrentBooking();
        } catch (NoBookingException $e) {
            $booking = new Booking();
            $this->session->set('booking', $booking);
        }

        return $booking;
    }

    /**
     * @param Booking $booking
     */
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

    /**
     * @param Booking $booking
     * @return Booking
     */
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

    /**
     * @param Request $request
     * @param Booking $booking
     * @return bool|mixed|null
     */
    public function doPayment(Request $request, Booking $booking)
    {
        $transactionId = $this->payment->payment($booking, $request->request->get('stripeToken'));
        if (false !== $transactionId) {
            $booking->setTransactionId($transactionId);
            $this->entityManager->persist($booking);
            $this->entityManager->flush();

            $this->mailerService->sendMail($booking);
        }

        return $transactionId;
    }

    /**
     * @return mixed
     * @throws NoBookingException
     */
    public function getCurrentBooking()
    {
        $booking = $this->session->get('booking'); //gérer le cas où pas de booking

        if (!$booking) {
            throw new NoBookingException();
        }
        return $booking;
    }

    /**
     * method to clear the session
     */
    public function clearSession()
    {
        $this->session->clear();
    }

    /**
     * @param Booking $booking
     * @return mixed
     */
    public function recoverBookingId(Booking $booking)
    {
        $id = $this->session->get('booking')->getId();
        return $id;
    }
}
