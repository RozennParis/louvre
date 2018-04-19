<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 19/04/18
 * Time: 08:39
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotHolydays extends Constraint
{
    public $message = 'La réservation n\'est pas possible à cette date {{ booking.bookingDate|date(\'d/m/Y\') }}';
}


