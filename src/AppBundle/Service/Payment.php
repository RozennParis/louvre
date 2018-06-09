<?php

namespace AppBundle\Service;

use AppBundle\Entity\Booking;

use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;


/**
 * Class Payment
 * @package AppBundle\Service
 */
class Payment
{
    private $secretKey;

    /**
     * Payment constructor.
     * @param $secretKey
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @param Booking $booking
     * @param $token
     * @return bool|mixed|null
     */
    public function payment(Booking $booking, $token)
    {
        Stripe::setApiKey($this->secretKey);


            // Create a charge: this will charge the user's card
        try {
            $charge = Charge::create([
            "amount" => $booking->getTotalPrice() * 100, // Amount in cents
            "currency" => "eur",
            "source" => $token,
            "description" => "Billetterie du Louvre"
            ]);

            return $charge['id'];

        } catch(Card $e) {

            return false;

        }
    }
}
