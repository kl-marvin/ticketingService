<?php

namespace AppBundle\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class BankHolidaysValidator extends ConstraintValidator
{

    public static function getHolidays($year = null)
    {

        if ($year === null) {
            $year = intval(strftime('%Y'));
        }

        $easterDate = easter_date($year);
        $easterDay = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear = date('Y', $easterDate);

        $holidays = array(
            // Jours feries fixes
            mktime(0, 0, 0, 1, 1, $year),// 1er janvier
            mktime(0, 0, 0, 5, 1, $year),// Fete du travail
            mktime(0, 0, 0, 5, 8, $year),// Victoire des allies
            mktime(0, 0, 0, 7, 14, $year),// Fete nationale
            mktime(0, 0, 0, 8, 15, $year),// Assomption
            mktime(0, 0, 0, 11, 1, $year),// Toussaint
            mktime(0, 0, 0, 11, 11, $year),// Armistice
            mktime(0, 0, 0, 12, 25, $year),// Noel

            // Jour feries qui dependent de paques
            mktime(0, 0, 0, $easterMonth, $easterDay + 1, $easterYear),// Lundi de paques
            mktime(0, 0, 0, $easterMonth, $easterDay + 39, $easterYear),// Ascension
            mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear), // Pentecote
        );

        sort($holidays);


        return $holidays;
    }


    public function validate($date, Constraint $constraint)
    {
        if ($date !== null) {
            $bankHoliday = static::getHolidays($date->format('Y'));

            foreach ($bankHoliday as $day){
                /**
                 * format U permet de récupérer un timestamp afin de comparer au tableau de la fonction getHolidays
                 */
                if ($date->format('U') == $day) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                }

            }
        }
    }
}
