<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotSundayValidator extends ConstraintValidator
{
    const SUNDAY = "7";

    public function validate($visitDate, Constraint $constraint)

    {
        if ( $visitDate->format('N') === self::SUNDAY)
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();

        }
    }

}
