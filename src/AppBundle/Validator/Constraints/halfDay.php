<?php


namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class halfDay
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class halfDay extends Constraint
{
    public $message = "il est 14h00 passés, seuls les billets à la demi-journée sont disponibles.";


    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
