<?php
namespace Comfort;

use Comfort\Validator\AbstractValidator;
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
     * Mapped validators
     *
     * @var array
     */
    protected static $registeredValidators = [];

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
        $class = $this->resolveValidator($name);
        return new $class($this);
    }

    public static function registerValidator($name, $class)
    {
        if (!is_subclass_of($class, AbstractValidator::class)) {
            throw new \RuntimeException($class . ' must extend ' . AbstractValidator::class);
        }

        self::$registeredValidators[$name] = $class;
    }

    /**
     * Return class name for validator
     *
     * @param $name
     * @return string
     */
    protected function resolveValidator($name)
    {
        if (array_key_exists($name, self::$registeredValidators)) {
            return self::$registeredValidators[$name];
        }

        $className = __NAMESPACE__ . '\\Validator\\' . ucfirst($name) . 'Validator';
        if (class_exists($className)) {
            return $className;
        }

        throw new \RuntimeException(sprintf('%s validator not found', $name));
    }
}
