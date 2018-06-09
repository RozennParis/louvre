<?php

namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * Class NotTuesday
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
 class NotTuesday extends Constraint
{
     /**
      * @var string
      */
    public $message = 'validator.not_tuesday';
}
