<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotHolidays extends Constraint
{
    public $message = 'validator.not_holidays';
}


