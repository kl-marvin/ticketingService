<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

class HalfdayBookingConstraint extends Constraint
{
     public $message = "Vous ne pouvez malheuresement plus réserver de billet Journée après 14h.";

}