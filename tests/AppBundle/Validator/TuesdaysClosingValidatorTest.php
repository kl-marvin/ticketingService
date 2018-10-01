<?php

namespace tests\AppBundle\Validator;

use AppBundle\Entity\Booking;
use AppBundle\Validator\TuesdaysClosing;
use AppBundle\Validator\TuesdaysClosingValidator;

class TuesdaysClosingValidatorTest extends ValidatorTestAbstract
{
  protected function getValidatorInstance()
  {
    return new TuesdaysClosingValidator();
  }

  public function testTuesdaysClosingValidatorKO()
  {
    $tuesdaysConstraint = new TuesdaysClosing();
    $normalDay = new \DateTime('2018-09-15'); // is not a Tuesday

    $tuesdaysValidator = $this->initValidator();

    $tuesdaysValidator->validate($normalDay, $tuesdaysConstraint); // ($valeur attendue ,$résultat a éxécuter)

  }

  public function testTuesdaysClosingValidatorOK()
  {
    $tuesdaysConstraint = new TuesdaysClosing();
    $tuesday = new \DateTime('2018-09-18'); // is a Tuesday
    $tuesdaysValidator = $this->initValidator($tuesdaysConstraint->message);

    $tuesdaysValidator->validate($tuesday, $tuesdaysConstraint);

  }


}
