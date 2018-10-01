<?php

namespace Tests\AppBundle\Validator;

use Symfony\Component\Validator\ContraintValidator;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;
use PHPUnit\Framework\TestCase;


abstract class ValidatorTestAbstract extends TestCase
{
  /**
  * @return ConstaintValidator
  */
  abstract protected function getValidatorInstance();

  protected function initValidator($expectedMessage = null)
  {
     $builder = $this->getMockBuilder(ConstraintViolationBuilder::class)
        ->disableOriginalConstructor()
        ->setMethods(['addViolation'])
        ->getMock();

      $context = $this->getMockBuilder(ExecutionContext::class)
        ->disableOriginalConstructor()
        ->setMethods(['buildViolation'])
        ->getMock();

      if ($expectedMessage) {
        $builder->expects($this->once())->method('addViolation');

        $context->expects($this->once())
          ->method('buildViolation')
          ->with($this->equalTo($expectedMessage))
          ->will($this->returnValue($builder));
      } else {
        $context->expects($this->never())->method('buildViolation');
      }

      $validator = $this->getValidatorInstance();
      $validator->initialize($context);

      return $validator;
  }
}
