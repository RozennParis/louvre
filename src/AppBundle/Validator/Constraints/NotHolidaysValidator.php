<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class NotHolidaysValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)

    {
        if ($value->holidays($value) === 'thuesday')// TODO: Implement validate() method.
        {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ booking.bookingDate|date(\'d/m/Y\') }}', $value)
                ->addViolation();

        }
    }

    public function holidays($value)
    {
        $weekday = getDate($value);
        return $weekday = $weekday['weekday'];

    }
}
