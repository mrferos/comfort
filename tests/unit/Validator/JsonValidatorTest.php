<?php
namespace ComfortTest\Validator;

use Comfort\Comfort;
use Comfort\ValidationError;
use Comfort\Validator\ArrayValidator;
use Comfort\Validator\JsonValidator;
use Comfort\Validator\AbstractValidator;

class JsonValidatorTest extends \PHPUnit_Framework_TestCase
{
    public function testKeysJsonDecodedFailure()
    {
        $comfortMock = $this->getMock(Comfort::class);
        $jsonValidator = new JsonValidator($comfortMock);
        $this->assertInstanceOf(
            ValidationError::class,
            $jsonValidator('{not:valid:json}')
        );
    }

    public function testKeysPipedToArray()
    {
        $arrayMock = $this->getMockBuilder(ArrayValidator::class)
                            ->disableOriginalConstructor()
                            ->getMock();
        $arrayMock->expects($this->once())
                    ->method('keys')
                    ->willReturn(function() {});

        $comfortMock = $this->getMock(Comfort::class);
        $comfortMock->expects($this->once())
                    ->method('__call')
                    ->with('array')
                    ->willReturn($arrayMock);

        $jsonValidator = new JsonValidator($comfortMock);
        $jsonValidator->keys([
            'first_name' => $arrayMock
        ]);

        $jsonValidator('{"rawr": "keys"}');
    }

    public function testItemsPipedToArray()
    {
        $arrayMock = $this->getMockBuilder(ArrayValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $testValidation = $this->getMockBuilder(AbstractValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $arrayMock->expects($this->once())
            ->method('items')
            ->with($testValidation)
            ->willReturn(function() {});

        $comfortMock = $this->getMock(Comfort::class);
        $comfortMock->expects($this->once())
            ->method('__call')
            ->with('array')
            ->willReturn($arrayMock);

        $jsonValidator = new JsonValidator($comfortMock);
        $jsonValidator->items($testValidation);

        $jsonValidator('{"rawr": "keys"}');
    }
}