<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 09/06/18
 * Time: 20:50
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class NotAfterNineValidator
 * @package AppBundle\Validator\Constraints
 */
class NotAfterNineValidator extends ConstraintValidator
{
    const WEDNESDAY = "3";
    const FRIDAY = "5";
    const NINE = '21:45';

    /**
     * @param mixed $visitDate
     * @param Constraint $constraint
     */
    public function validate($booking, Constraint $constraint)
    {

        if ($booking->getVisitDate()->format('Y-m-d') === date('Y-m-d')  && date('H:i')>= self::NINE)
        {
            if(($booking->getVisitDate()->format('N') === self::WEDNESDAY) | ($booking->getVisitDate()->format('N') === self::FRIDAY))
            {
                $this->context->buildViolation($constraint->message)
                    ->atPath('visitDate')
                    ->addViolation();
            }
        }
    }
}

