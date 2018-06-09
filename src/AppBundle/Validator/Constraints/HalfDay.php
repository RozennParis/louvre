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
    /**
     * @var string
     */
    public $message = 'validator.half_day';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
