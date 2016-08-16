<?php
namespace ComfortTest;

use Comfort\Comfort;
use Comfort\Validator\AbstractValidator;
use Comfort\Validator\ArrayValidator;
use Comfort\Validator\JsonValidator;
use Comfort\Validator\NumberValidator;
use Comfort\Validator\StringValidator;
use RuntimeException;

class ComfortTestCustomerValidator extends AbstractValidator{}

class ComfortTest extends \PHPUnit_Framework_TestCase
{
    public function testGettingValidator()
    {
        $types = [
            'string' => StringValidator::class,
            'array'  => ArrayValidator::class,
            'json'   => JsonValidator::class,
            'number' => NumberValidator::class,
        ];

        $comfort = new Comfort();
        foreach ($types as $type => $instance) {
            $this->assertInstanceOf($instance, $comfort->__call($type, null));
        }
    }

    public function testGettingInvalidValidator()
    {
        $this->setExpectedException(RuntimeException::class);

        $comfort = new Comfort();
        $comfort->__call('eadsin', null);
    }

    public function testRegisterValidator()
    {
        Comfort::registerValidator('flex', ComfortTestCustomerValidator::class);
        $reflClass = new \ReflectionClass(Comfort::class);
        $staticProperties = $reflClass->getStaticProperties();

        $this->assertEquals(
            [
                'registeredValidators' => [
                    'flex' => ComfortTestCustomerValidator::class
                ]
            ],
            $staticProperties
        );
    }

    public function testRegisterInvalidValidatorExceptions()
    {
        $this->setExpectedException(RuntimeException::class);

        Comfort::registerValidator('flex', \stdClass::class);
    }

    public function testResolveCustomValidator()
    {
        Comfort::registerValidator('flex', ComfortTestCustomerValidator::class);

        $cmf = new Comfort();
        $this->assertInstanceOf(ComfortTestCustomerValidator::class, $cmf->flex());
    }
}