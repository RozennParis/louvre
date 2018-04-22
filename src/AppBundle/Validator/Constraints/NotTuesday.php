<?php

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class NotATuesday
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
 class NotTuesday extends Constraint
{
    public $message = 'Le musée est fermé le mardi';
}
