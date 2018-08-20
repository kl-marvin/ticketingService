<?php

namespace AppBundle\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * Class SundaysClosing
 * @package AppBundle\Validator
 * @Annotation
 */
class SundaysClosing extends Constraint
{
    public $message = "constraint.SundayConstraint";
}