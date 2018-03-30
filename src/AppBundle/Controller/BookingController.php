<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use AppBundle\Form\BookingType;
use AppBundle\Form\TicketsType;
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
    public function ticketAction(Request $request, SessionInterface $session)
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
                //enregistrement en session
                $this->get('session')->set('ticket', $booking);
                echo '<pre>'; var_dump($booking->getTickets()); die;

                //go to step3
                return $this->redirectToRoute('summary');
        }

        return $this->render('Booking/ticket.html.twig', [
                'form'=>$ticketForm->createView()
        ]);
    }

    /**
     * @Route("/summary", name="summary")
     * @Method({"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function summaryAction(Request $request, SessionInterface $session)
    {

    }
}


