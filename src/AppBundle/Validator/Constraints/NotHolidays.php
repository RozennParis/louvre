<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotHolidays extends Constraint
{
    /**
     * @var string
     */
    public $message = 'validator.not_holidays';
}


