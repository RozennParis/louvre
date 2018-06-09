<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class NotHolidaysValidator
 * @package AppBundle\Validator\Constraints
 */
class NotHolidaysValidator extends ConstraintValidator
{
    private $holidays;
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($visitDate, Constraint $constraint)

    {
        if (!$visitDate instanceof \Datetime)
        {
            $visitDate = new \DateTime($visitDate);
        }


        $year = (int) $visitDate->format('Y');

        if (!$year)
        {
            $year = intval(date('Y'));
        }

        $easterDate = easter_date($year);
        $easterDay = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear = date ('Y', $easterDate);

        $holidays = [
            mktime(0, 0, 0, 1, 1, $year),
            mktime(0, 0, 0, 5, 1, $year),
            mktime(0, 0, 0, 5, 8, $year),
            mktime(0, 0, 0, 7, 14, $year),
            mktime(0, 0, 0, 8, 15, $year),
            mktime(0, 0, 0, 11, 1, $year),
            mktime(0, 0, 0, 11, 11, $year),
            mktime(0, 0, 0, 12, 25, $year),
            mktime(0, 0, 0, $easterMonth, $easterDay + 1, $year),
            mktime(0, 0, 0, $easterMonth, $easterDay + 39, $year),
            mktime(0, 0, 0, $easterMonth, $easterDay + 50, $year),
        ];

        if (in_array($visitDate->getTimestamp(), $holidays, true))
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
