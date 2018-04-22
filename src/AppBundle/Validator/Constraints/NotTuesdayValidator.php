<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 20/04/18
 * Time: 17:21
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotTuesdayValidator extends ConstraintValidator
{
    const TUESDAY = "2";

    public function validate($visitDate, Constraint $constraint)

    {

        if ( $visitDate->format('N') === self::TUESDAY)// TODO: Implement validate() method.
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();

        }
    }

}
