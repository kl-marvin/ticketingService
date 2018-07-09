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
    public $message = "Après 17h, vous ne pouvez plus réserver de billet pour la journée en cours.";

}