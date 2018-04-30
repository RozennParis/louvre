<?php


namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;


/**
 * Class NotSunday
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class NotSunday extends Constraint
{
    public $message = 'validator.not_sunday';
}

