<?php
/**
 * Created by PhpStorm.
 * User: rozenn
 * Date: 25/04/18
 * Time: 10:35
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
/**
 * Class NotMoreThousand
 * @package AppBundle\Validator\Constraints
 * @Annotation
 */
class NotMoreThousand extends Constraint
{
    public $message = 'Il n\'est plus possible de réserver, le quota a été atteint';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
