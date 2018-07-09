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
    public $message = "Vous ne pouvez pas réserver de billet lors d'un jour férié. ";
}