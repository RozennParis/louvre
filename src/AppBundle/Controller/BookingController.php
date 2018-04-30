<?php

namespace AppBundle\Controller;

use AppBundle\Form\BookingType;
use AppBundle\Form\TicketsType;
use AppBundle\Manager\BookingManager;
use AppBundle\Service\Payment;
use AppBundle\Service\MailerService;
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
        $booking = $bookingManager->getBookingFromSession();

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
        $booking = $bookingManager->getBookingFromSession();


       if ($request->getMethod() === Request::METHOD_POST)
       {
           if($bookingManager->doPayment($request, $booking)){
               //$this->addFlash("success","Le paiement a bien été effectué !");
               return $this->redirectToRoute('final_summary', [
                   'id'=> $booking->getId()
               ]);
           }

       }
        return $this->render('Booking/summary.html.twig', [
            'booking'=>$booking
        ]);
    }



    /**
     * @Route("/final-summary/{id}", name="final_summary")
     * @Method({"GET", "POST"}) //enlever le GET, juste pour tester au rafraichissement de la page
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function finalSummaryAction(BookingManager $bookingManager)
    {
        $booking = $bookingManager->getBookingFromSession();
        $bookingManager->clearSession();


        return $this->render('Booking/final-summary.html.twig', [
            'booking'=>$booking,
        ]);


    }

    /**
    * @Route("/sale", name="sale")
    * @Method({"GET"})
    */
    public function saleAction()
    {
        return $this->render('StaticViews/general-terms-of-sale.html.twig');
    }

    /**
     * @Route("/news", name="news")
     * @Method({"GET"})
     */
    public function newsAction()
    {
        return $this->render('StaticViews/practical-news.html.twig');
    }


}


