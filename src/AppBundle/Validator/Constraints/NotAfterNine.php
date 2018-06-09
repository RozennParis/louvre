<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class NotAfterNine
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class NotAfterNine extends Constraint

{
    /**
     * @var string
     */
    public $message = 'validator.not_after_eight';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
