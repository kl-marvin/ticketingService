<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class BankHolidays
 * @package AppBundle\Validator
 * @Annotation
 */
class BankHolidays extends Constraint
{
    public $message = "constraint.BankHolidaysConstraint";
}
