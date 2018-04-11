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
    private $doctrine;

    /**
     * @var FormFactoryInterface
     */
    private $form;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(EntityManagerInterface $doctrine, FormFactoryInterface $form, SessionInterface $session, Tarificator $tarificator, AgeCalculator $ageCalculator)
    {
        $this->doctrine = $doctrine;
        $this->form = $form;
        $this->session = $session;
        $this->tarificator = $tarificator;
        $this->ageCalculator = $ageCalculator;
    }

    public function booking(Request $request)
    {
        $booking = new Booking();
        $bookingForm = $this->createForm(BookingType::class, $booking);

        $bookingForm->handleRequest($request);

        if($bookingForm->isSubmitted() && $bookingForm->isValid()) {
            // préparer le nombre de tickets
            $count = $booking->getNumberOfTickets();
            for ($i = 1; $i <= $count; $i++) {
                $booking->addTicket(new Ticket());
            }
            //sauvegarder en session booking
            $this->get('session')->set('booking',$booking);

        }
        return $bookingForm;
    }

    public function ticket(Request $request)
    {

        $booking = $this->get('session')->get('booking'); //gérer le cas où pas de booking

        if (!$booking)
        {
            throw $this->createNotFoundException("La commande n'a pas été initialisée");
        }

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
}
