<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 23/04/18
 * Time: 14:12
 */

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HalfDayValidator extends ConstraintValidator
{
    const HALF_DAY = 14;

    public function validate($booking, Constraint $constraint)
    {

       if ($booking->getVisitDate()->format('Y-m-d') === date('Y-m-d') && date('H') >= self::HALF_DAY)
        {
            $booking->setTypeOfTicket(false);
            $this->context->buildViolation($constraint->message)
                ->atPath('typeOfTicket')
                ->addViolation();
        }
    }


}
