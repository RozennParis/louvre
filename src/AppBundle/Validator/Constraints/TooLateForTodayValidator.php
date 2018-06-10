<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class TooLateForTodayValidator
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class TooLateForTodayValidator extends ConstraintValidator

{
    const MONDAY = "1";
    const WEDNESDAY = "3";
    const THURSDAY = "4";
    const FRIDAY = "5";
    const SATURDAY = "6";
    const EARLY_CLOSE = '11:00';
    const LATE_CLOSE = '11:45';


    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $booking
     * @param Constraint $constraint
     */
    public function validate($booking, Constraint $constraint)
    {

        if ($booking->getVisitDate()->format('Y-m-d') === date('Y-m-d') && date('H:i') >= self::EARLY_CLOSE){
            switch ($booking->getVisitDate()->format('N')){
                case (self::MONDAY | self::THURSDAY | self::SATURDAY):
                case (self::WEDNESDAY | self::FRIDAY  && date('H:i') >= self::LATE_CLOSE):
                    $this->context->buildViolation($constraint->message)
                        ->atPath('visitDate')
                        ->addViolation();
                    break;
            }
        }
    }
}

