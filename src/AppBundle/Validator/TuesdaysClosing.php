<?php

namespace AppBundle\Validator;


use Symfony\Component\Validator\Constraint;


/**
 * Class TuesdaysClosing
 * @package AppBundle\Validator
 * @Annotation
 */
class TuesdaysClosing extends Constraint
{
      public $message = "constraint.TuesdayConstraint";

}