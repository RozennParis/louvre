<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 05/04/18
 * Time: 16:47
 */

namespace AppBundle\Services;

use AppBundle\Entity\Booking;
use AppBundle\Entity\Ticket;


class ageCalculator
{
    public function ageCalcul($visitDate, $birthDate)
    {
        $age = date_diff($visitDate, $birthDate);

        $age = get_object_vars($age);
        $age = $age['y'];

        return $age;
    }
}
