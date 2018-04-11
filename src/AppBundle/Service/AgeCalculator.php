<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 11/04/18
 * Time: 14:25
 */

namespace AppBundle\Service;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;

class AgeCalculator
{
    public function ageCalcul($visitDate, $birthDate)
    {
        $age = date_diff($visitDate, $birthDate);
        $age = get_object_vars($age);
        $age = $age['y'];

        return $age;
    }
}
