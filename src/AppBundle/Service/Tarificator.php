<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 29/03/18
 * Time: 15:07
 */

namespace AppBundle\Service;

use AppBundle\Service\AgeCalculator;


class Tarificator
{
    const ADULT_PRICE = 16;
    const CHILD_UNDER_4_PRICE = 0;
    const CHILD_PRICE = 8;
    const SENIOR_PRICE = 12;
    const REDUCE_PRICE = 10;
    private $price;


    public function priceOfTicket($reduceRate,$age, $typeOfTicket)
    {
        if (!$reduceRate) {
            switch ($age) {
                case ($age>= 12 && $age< 60) :
                    $price = self::ADULT_PRICE;
                    break;
                case ($age< 4):
                    $price = self::CHILD_UNDER_4_PRICE;
                    break;
                case ($age>= 4 && $age< 12) :
                    $price = self::CHILD_PRICE;
                    break;
                case ($age>= 60) :
                    $price = self::SENIOR_PRICE;
                    break;
            }
        }
        else {
            $price = self::REDUCE_PRICE;
        }

        if ($typeOfTicket === false )
        {
            $price = $price/2;
        }
        return $price;
    }
}
