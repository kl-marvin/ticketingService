<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class SoldOut
 * @package AppBundle\Validator
* @Annotation
 */
 class SoldOut extends Constraint
 {
   public $message = "constraint.SoldOut";

   public function getTargets()
   {
     return self::CLASS_CONSTRAINT;
   }
 }
