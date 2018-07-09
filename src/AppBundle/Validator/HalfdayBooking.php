<?php

namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;


/**
 * Class HalfdayBooking
 * @package AppBundle\Validator
* @Annotation
 */
class HalfdayBooking extends Constraint
{
     public $message = "Vous ne pouvez malheuresement plus réserver de billet Journée après 14h.";

    // la contrainte s'applique à l'échelle d'une classe et non d'un attribut
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}