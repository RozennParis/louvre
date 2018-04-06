<?php

namespace AppBundle\Services;

use AppBundle\Entity\Booking;

use Stripe\Charge;
use Stripe\Error\Card;
use Stripe\Stripe;


class Payment
{
    private $secretKey;

    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function payment(Booking $booking, $token)
    {
        Stripe::setApiKey($this->secretKey);


            // Create a charge: this will charge the user's card
        try {
            $charge = Charge::create([
            "amount" => $booking->getTotalPrice() * 100, // Amount in cents
            "currency" => "eur",
            "source" => $token,
            "description" => "Stripe payment"
            ]);

            return $charge['id'];

        } catch(Card $e) {

            return false;

        }
    }
}
