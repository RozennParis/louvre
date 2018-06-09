<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class NotSundayValidator
 * @package AppBundle\Validator\Constraints
 */
class NotSundayValidator extends ConstraintValidator
{
    const SUNDAY = "7";

    /**
     * @param mixed $visitDate
     * @param Constraint $constraint
     */
    public function validate($visitDate, Constraint $constraint)

    {
        if ( $visitDate->format('N') === self::SUNDAY)
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();

        }
    }

}
