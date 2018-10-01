<?php

namespace Tests\AppBundle\Validator;

use AppBundle\Entity\Booking;
use AppBundle\Validator\BankHolidays;
use AppBundle\Validator\BankHolidaysValidator;

class BankHolidaysValidatorTest extends ValidatorTestAbstract
{
  protected function getValidatorInstance()
  {
    return new BankHolidaysValidator();
  }

  public function testBankHolidaysValidatorKO()
  {
    $bankHolidaysConstraint = new BankHolidays();
    $normalDay = new \Datetime('2018-09-15');

    $bankHolidaysValidator = $this->initValidator();

    $bankHolidaysValidator->validate($normalDay, $bankHolidaysConstraint);
  }

  public function testBankHolidaysValidatorOK()
  {
    $bankHolidaysConstraint = new BankHolidays();
    $christmas = new \DateTime('2018-12-25');

    $bankHolidaysValidator = $this->initValidator($bankHolidaysConstraint->message);

    $bankHolidaysValidator->validate($christmas, $bankHolidaysConstraint);
  }
}
