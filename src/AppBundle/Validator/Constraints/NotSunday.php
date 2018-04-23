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
    public $message = 'La réservation n\'est pas disponible le dimanche';
}

