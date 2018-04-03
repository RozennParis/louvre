<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 29/03/18
 * Time: 15:07
 */

namespace AppBundle\Services;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Tarificator
{
    const ADULT_PRICE = 16;
    const CHILD_UNDER_4_PRICE = 0;
    const CHILD_PRICE = 8;
    const SENIOR_PRICE = 12;
    const REDUCE_PRICE = 10;

    private $totalPrice = 0;

    public function ageCalcul($visitDate, $birthDate, $differenceFormat = '%y')
    {
        $age = date_diff($visitDate, $birthDate);
        return $age->format($differenceFormat);
    }


    public function priceOfTicket($reduceRate, $age)
    {

        if (!$reduceRate) {
            switch ($age) {
                case ($age >= 12 && $age < 60) :
                    $price = self::ADULT_PRICE;
                    break;
                case ($age < 4):
                    $price = self::CHILD_UNDER_4_PRICE;
                    break;
                case ($age >= 4 && $age < 12) :
                    $price = self::CHILD_PRICE;
                    break;
                case ($age >= 60) :
                    $price = self::SENIOR_PRICE;
                    break;
            }
        }
        else {
            $price = self::REDUCE_PRICE;
        }
        return $price;
    }

   public function bookingPrice(Booking $booking, $tickets)
    {
        $totalPrice = 0;
        $tickets->toArray();
        for ($i = 0; $i < $booking->getNumberOfTickets(); $i++)
        {
            $price = $tickets[$i]->getPrice();
            $totalPrice = $totalPrice + $price;
        }

        return $totalPrice;
    }

}
