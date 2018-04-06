<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Form\BookingType;
use AppBundle\Form\TicketsType;
use AppBundle\Services\ageCalculator;
use AppBundle\Services\Tarificator;
use AppBundle\Services\Payment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BookingController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @Method({"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, SessionInterface $session)
    {
        $booking = new Booking();
        $bookingForm = $this->createForm(BookingType::class, $booking);

        $bookingForm->handleRequest($request);

        if($bookingForm->isSubmitted() && $bookingForm->isValid())
        {
            // préparer le nombre de tickets
            for ($i = 1; $i <= $booking->getNumberOfTickets(); $i++)
            {
                $booking->addTicket(new Ticket());
            }
            //sauvegarder en session booking
            $this->get('session')->set('booking', $booking);

            // rediriger vers step2
            return $this->redirectToRoute('ticket');

        }

        // replace this example code with whatever you need
        return $this->render('Booking/index.html.twig', [
            'form'=>$bookingForm->createView()
        ]);
    }

    /**
     * @Route("/ticket", name="ticket")
     * @Method({"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ticketAction(Request $request, SessionInterface $session, Tarificator $tarificator, ageCalculator $ageCalculator)
    {
        /**
         * @var Booking $booking
         */
        $booking = $session->get('booking'); //gérer le cas où pas de booking

        if (!$booking)
        {
            throw $this->createNotFoundException("La commande n'a pas été initialisée");
        }

        $ticketForm = $this->createForm(TicketsType::class, $booking);
        $ticketForm->handleRequest($request);

        if($ticketForm->isSubmitted() && $ticketForm->isValid())
        {
            //ticket price and total price
            $tickets = $booking->getTickets();
            $totalPrice = 0;
            foreach ($tickets as $ticket)
            {
                $age = $ageCalculator->ageCalcul($booking->getVisitDate(), $ticket->getBirthdate());
                $ticket->setAge($age);

                $price = $tarificator->priceOfTicket($ticket->getReduceRate(), $ticket->getAge());
                $ticket->setPrice($price);

                $totalPrice += $ticket->getPrice();
            }

            $booking->setTotalPrice($totalPrice);

            //session registration

            $this->get('session')->set('booking', $booking);

            //go to step3
            return $this->redirectToRoute('summary');
        }

        return $this->render('Booking/ticket.html.twig', [
                'form'=>$ticketForm->createView()
        ]);
    }

    /**
     * @Route(path="/summary", name="summary")
     * @Method({"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function summaryAction(Request $request, SessionInterface $session, Payment $payment)
    {
        $booking = $session->get('booking');
        $tickets = $booking->getTickets();

       if ($request->getMethod() === Request::METHOD_POST)
       {
           $transactionId = $payment->payment($booking, $request->request->get('stripeToken'));
           if (false !== $transactionId)
           {
               /*persist et flush
            si ok >>> envoi mail*/
            $this->addFlash("success","Le paiement a bien été effectué !");
           return $this->redirectToRoute('final_summary');
           }
       }

        return $this->render('Booking/summary.html.twig', [
            'booking'=>$booking,
            'tickets'=>$tickets
        ]);
    }



    /**
     * @Route("/final-summary", name="final_summary")
     * @Method({"GET", "POST"}) //enlever le GET, juste pour tester au rafraichissement de la page
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function finalSummaryAction(Request $request, SessionInterface $session)
    {
        $booking = $session->get('booking');
        $tickets = $booking->getTickets();

        return $this->render('Booking/final-summary.html.twig', array(
            'booking'=>$booking,
            'tickets'=>$tickets
        ));


    }
}


