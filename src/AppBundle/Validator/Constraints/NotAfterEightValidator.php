<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class NotAfterEightValidator
 * @package AppBundle\Validator\Constraints
 */
class NotAfterEightValidator extends \Symfony\Component\Validator\ConstraintValidator
{
    const MONDAY = "1";
    const WEDNESDAY = "3";
    const THURSDAY = "4";
    const FRIDAY = "5";
    const SATURDAY = "6";
    const EIGHTEEN = 18;
    const TWENTY_ONE = '21:45';

    /**
     * @param mixed $visitDate
     * @param Constraint $constraint
     */
    public function validate($booking, Constraint $constraint)
    {

        if ($booking->getVisitDate()->format('Y-m-d') === date('Y-m-d')  && date('H')>= self::EIGHTEEN)
        {
            if(($booking->getVisitDate()->format('N') === self::MONDAY) | ($booking->getVisitDate()->format('N') === self::THURSDAY) | ($booking->getVisitDate()->format('N') === self::SATURDAY))
            {
                $this->context->buildViolation($constraint->message)
                    ->atPath('visitDate')
                    ->addViolation();
           }
        }
    }
}

