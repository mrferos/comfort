<?php
namespace Comfort;

use Comfort\Validator\AnyValidator;
use Comfort\Validator\ArrayValidator;
use Comfort\Validator\JsonValidator;
use Comfort\Validator\NumberValidator;
use Comfort\Validator\StringValidator;

/**
 * Class Comfort
 * @package Comfort
 * @method ArrayValidator array()
 * @method StringValidator string()
 * @method JsonValidator json()
 * @method NumberValidator number()
 * @method AnyValidator any()
 */
class Comfort
{
    /**
     * Returns validator for given data type
     *
     * @param $name
     * @param $arguments
     * @return ArrayValidator|JsonValidator|StringValidator|AnyValidator
     * @throws \RuntimeException
     */
    public function __call($name, $arguments)
    {
        switch ($name) {
            case 'array':
                return new ArrayValidator($this);
            case 'string':
                return new StringValidator($this);
            case 'json':
                return new JsonValidator($this);
            case 'number':
                return new NumberValidator($this);
            case 'any':
                return new AnyValidator($this);
            default:
                throw new \RuntimeException('Unsupported data type');
        }
    }
}