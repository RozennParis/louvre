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

class halfDayValidator extends ConstraintValidator
{

   public function validate($booking, Constraint $constraint)
    {

       if ($booking->getVisitDate()->format('Y-m-d') === $booking->getBookingDate()->format('Y-m-d'))
        {
            /*if($booking->getBookingDate->format('H:i:s') >= time())
            {
                $typeOfTicket = self::TYPE_OF_TICKET_HALFDAY;*/
                $this->context->buildViolation($constraint->message)
                    ->atPath('TicketType')
                    ->addViolation();
            //}
        }
    }


}
