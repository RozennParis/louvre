<?php

namespace AppBundle\Controller;

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
        $form = $bookingManager->booking($request);

        if($form->isSubmitted() && $form->isValid())
        {
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
        $form = $bookingManager->ticket($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //go to step3
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


