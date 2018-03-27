<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Booking;
use AppBundle\Form\BookingType;
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
            //sauvegarder en session booking
            // rediriger vers step2
            return $this->render('Booking/ticket.html.twig', [
                'form'=>$bookingForm->createView()
            ]);
        }

        // replace this example code with whatever you need
        return $this->render('Booking/index.html.twig', [
            'form'=>$bookingForm->createView()
        ]);
    }
}
