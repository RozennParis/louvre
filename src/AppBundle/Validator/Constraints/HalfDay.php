<?php


namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class halfDay
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class HalfDay extends Constraint
{
    public $message = 'validator.half_day';


    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
