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
     * @var FormFactoryInterface
     */
    private $form;

    /**
     * @var SessionInterface
     */
    private $session;
    private $tarificator;
    private $ageCalculator;
    /**
     * @var Payment
     */
    private $payment;

    public function __construct(EntityManagerInterface $em, FormFactoryInterface $form, SessionInterface $session, Tarificator $tarificator, AgeCalculator $ageCalculator, Payment $payment)
    {
        $this->entityManager = $em;
        $this->form = $form;
        $this->session = $session;
        $this->tarificator = $tarificator;
        $this->ageCalculator = $ageCalculator;
        $this->payment = $payment;
    }

    public function initBooking()
    {
        try {
            $booking = $this->getBookingFormSession();
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


    public function ticket(Request $request)
    {

        $booking = $this->getBookingFormSession();

        $ticketForm = $this->createForm(TicketsType::class, $booking);
        $ticketForm->handleRequest($request);

        if($ticketForm->isSubmitted() && $ticketForm->isValid()) {
            //ticket price and total price
            $tickets = $booking->getTickets();

            $totalPrice = 0;
            foreach ($tickets as $ticket) {
                $age = $this->ageCalculator->ageCalcul($booking->getVisitDate(), $ticket->getBirthdate());
                $ticket->setAge($age);

                $price = $this->tarificator->priceOfTicket($ticket->getReduceRate(), $ticket->getAge());
                $ticket->setPrice($price);

                $totalPrice += $ticket->getPrice();
            }
            $booking->setTotalPrice($totalPrice);
            $this->get('session')->set('booking', $booking);
        }
        return $ticketForm;
    }


    public function summary(Request $request)
    {
        $booking = $this->session->get('booking');

        $tickets = $booking->getTickets();

        if ($request->getMethod() === Request::METHOD_POST)
        {
            $transactionId = $this->payment->payment($booking, $request->request->get('stripeToken'));
            if (false !== $transactionId)
            {
                $this->entityManager->persist($booking);
                $this->entityManager->flush();

                //si ok >>> envoi mail
                // vide la session en gardant $id de coté
                //$this->addFlash("success","Le paiement a bien été effectué !");
            }
        }
        return $booking;
    }

    public function finalSummary(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // récupération de $id pour accéder aux données $booking en bdd
        $booking = $em->getRepository('AppBundle:Booking')->getBookingWithTickets($id);
        return $booking;
    }

    /**
     * @return mixed
     */
    private function getBookingFormSession()
    {
        $booking = $this->get('session')->get('booking'); //gérer le cas où pas de booking

        if (!$booking) {
            throw new NoBookingException();
        }
        return $booking;
    }
}
