<?php

namespace AppBundle\Controller;

use AppBundle\Form\BookingType;
use AppBundle\Form\TicketsType;
use AppBundle\Manager\BookingManager;
use AppBundle\Service\Payment;
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
    public function indexAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->initBooking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $bookingManager->completeInit($booking);
            $bookingManager->setBookingSession($booking);

            return $this->redirectToRoute('ticket');
        }

        return $this->render('Booking/index.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/ticket", name="ticket")
     * @Method({"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ticketAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->initBooking();

        $form = $this->createForm(TicketsType::class, $booking);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $bookingManager->getTicketPrice($booking);
            $bookingManager->setBookingSession($booking);

            return $this->redirectToRoute('summary');
        }

        return $this->render('Booking/ticket.html.twig', [
                'form'=>$form->createView()
        ]);
    }

    /**
     * @Route(path="/summary", name="summary", schemes={"%secure_channel%"})
     * @Method({"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function summaryAction(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->initBooking();
        $tickets = $bookingManager->getTicketsFromSession($booking);


       if ($request->getMethod() === Request::METHOD_POST)
       {
           $bookingManager->getBookingSummary($request, $booking);
           $id = $bookingManager->recoverBookingId($booking);

           $bookingManager->clearSession();

           return $this->redirectToRoute('final_summary', [
               'id'=> $id
           ]);
       }

        return $this->render('Booking/summary.html.twig', [
            'booking'=>$booking,
            'tickets'=>$tickets
        ]);
    }



    /**
     * @Route("/final-summary/{id}", name="final_summary")
     * @Method({"GET", "POST"}) //enlever le GET, juste pour tester au rafraichissement de la page
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function finalSummaryAction(BookingManager $bookingManager, $id)
    {
        $booking = $bookingManager->getFinalSummary($id);
        $tickets = $booking->getTickets();

        return $this->render('Booking/final-summary.html.twig', [
            'booking'=>$booking,
            'tickets'=>$tickets
        ]);


    }
}


