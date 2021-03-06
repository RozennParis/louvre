<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 17/04/18
 * Time: 15:15
 */

namespace AppBundle\Service;

use Swift_Mailer;
use Swift_Message;
use Swift_Image;
use Twig_Environment;
use AppBundle\Entity\Booking;

/**
 * Class MailerService
 * @package AppBundle\Service
 */
class MailerService
{
    private $mailer;
    private $twig;

    /**
     * MailerService constructor.
     * @param Swift_Mailer $mailer
     * @param Twig_Environment $twig
     */
    public function __construct( Swift_Mailer $mailer, Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param Booking $booking
     */
    public function sendMail(Booking $booking)
    {
        $message = (new Swift_Message())
            ->setSubject('Votre commande de billets d\'entrée pour le Louvre ')
            ->setFrom('rozenn.paris@gmail.com')
            ->setTo($booking->getEmail());

        $louvreLogo = $message->embed(Swift_image::fromPath('images/logo-louvre.jpg'));

        $message
            ->setBody(
                $this->twig->render(
                    'Booking/bookingEmail.html.twig', [
                        'booking'=> $booking,
                        'tickets'=> $booking->getTickets(),
                        'louvreLogo'=>$louvreLogo
                    ]
                ),
                'text/html',
                'UTF-8'
            );

        $this->mailer->send($message);
    }


}
