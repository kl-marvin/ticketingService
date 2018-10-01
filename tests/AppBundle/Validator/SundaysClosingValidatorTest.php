<?php

namespace Tests\AppBundle\Validator;

use AppBundle\Entity\Booking;
use AppBundle\Validator\SundaysClosing;
use AppBundle\Validator\SundaysClosingValidator;


class SundaysClosingValidatorTest extends ValidatorTestAbstract
{
  protected function getValidatorInstance()
  {
    return new SundaysClosingValidator();
  }

  public function testSundaysClosingValidatorKO()
  {
    $sundaysConstraint = new SundaysClosing();
    $closedDay = new \DateTime('2018-09-17'); // is not a Sunday

    $sundaysValidator = $this->initValidator();

    $sundaysValidator->validate($closedDay, $sundaysConstraint); // ($valeur attendue ,$résultat a éxécuter)
  }


  public function testSundaysClosingValidatorOK()
  {

   $sundaysConstraint = new SundaysClosing();
   $closedDay = new \DateTime('2018-09-16'); // is a Sunday
   $sundaysValidator = $this->initValidator($sundaysConstraint->message);

   $sundaysValidator->validate($closedDay, $sundaysConstraint);

  }

}
