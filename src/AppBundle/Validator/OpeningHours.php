<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * Class OpeningHours
 * @package AppBundle\Validator
 * @Annotation
 */
class OpeningHours extends Constraint
{
    public $message = "constraint.OpeningConstraint";

}