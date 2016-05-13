<?php
namespace Comfort;

use Comfort\Validator\ArrayValidator;
use Comfort\Validator\JsonValidator;
use Comfort\Validator\StringValidator;

/**
 * Class Comfort
 * @package Comfort
 * @method ArrayValidator array()
 * @method StringValidator string()
 * @method JsonValidator json()
 */
class Comfort
{
    /**
     * Returns validator for given data type
     *
     * @param $name
     * @param $arguments
     * @return ArrayValidator|JsonValidator|StringValidator
     */
    public function __call($name, $arguments)
    {
        switch($name) {
            case 'array':
                return new ArrayValidator($this);
            case 'string':
                return new StringValidator($this);
            case 'json':
                return new JsonValidator($this);
        }
    }
}