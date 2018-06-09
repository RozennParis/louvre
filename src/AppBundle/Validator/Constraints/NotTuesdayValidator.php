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

/**
 * Class NotTuesdayValidator
 * @package AppBundle\Validator\Constraints
 */
class NotTuesdayValidator extends ConstraintValidator
{
    const TUESDAY = "2";

    /**
     * @param mixed $visitDate
     * @param Constraint $constraint
     */
    public function validate($visitDate, Constraint $constraint)
    {

        if ( $visitDate->format('N') === self::TUESDAY)
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();

        }
    }

}
