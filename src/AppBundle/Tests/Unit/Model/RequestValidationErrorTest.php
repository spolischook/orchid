<?php

namespace AppBundle\Tests\Unit\Model;

use AppBundle\Model\RequestValidationErrorList;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class RequestValidationErrorTest extends TestCase
{
    public function testHasErrors()
    {
        $model = new RequestValidationErrorList();
        $this->assertFalse($model->hasErrors());

        $violation = new ConstraintViolation('Bad value', 'Bad value', [], null, null, null);
        $model->addQueryErrors('firstName', new ConstraintViolationList([$violation]));

        $this->assertTrue($model->hasErrors());
    }
}
