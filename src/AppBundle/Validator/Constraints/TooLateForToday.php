<?php
namespace AppBundle\Validator\Constraints;

use \Symfony\Component\Validator\Constraint;
/**
 * Class TooLateForToday
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class TooLateForToday extends \Symfony\Component\Validator\Constraint
{
    /**
     * @var string
     */
    public $message = 'validator.too_late_for_today';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
