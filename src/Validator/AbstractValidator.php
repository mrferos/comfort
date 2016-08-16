<?php
namespace Comfort\Validator;

use Comfort\Comfort;
use Comfort\Error;
use Comfort\Exception\DiscomfortException;
use Comfort\Exception\ValidationException;
use Comfort\ValidationError;
use Comfort\Validator\Helper\AlternativesTrait;

/**
 * Class AbstractValidator
 * @package Comfort\Validator
 */
abstract class AbstractValidator
{
    use AlternativesTrait;

    /**
     * @var \Closure[]
     */
    protected $validationStack = [];
    /**
     * @var Comfort
     */
    private $comfort;
    /**
     * @var boolean
     */
    private $toBool = false;
    /**
     * @var bool
     */
    private $optional = true;
    /**
     * @var mixed
     */
    private $defaultValue;
    /**
     * @var array
     */
    protected $errorHandlers = [
        'default' => [
            'message' => 'There was a validation error'
        ],
        'required' => [
            'message' => '%s is required',
            'default' => 'value'
        ]
    ];

    public function __construct(Comfort $comfort)
    {

        $this->comfort = $comfort;
    }

    /**
     * Execute validation stack
     *
     * @param mixed $value
     * @param null|string $key
     * @return bool|ValidationError|null
     */
    public function __invoke($value, $key = null)
    {
        if (is_null($value) && $this->optional) {
            if (is_null($this->defaultValue)) {
                return null;
            } else {
                $value = $this->defaultValue;
            }
        }

        try {
            reset($this->validationStack);

            do {
                /** @var callable $validator */
                $validator = current($this->validationStack);
                $retVal = $validator($value, $key);
                $value = $retVal === null ? $value : $retVal;
            } while (next($this->validationStack));

            if ($this->toBool) {
                return true;
            }

            return $value;
        } catch (ValidationException $validationException) {
            if ($this->toBool) {
                return false;
            }

            return ValidationError::fromException($validationException);
        }
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->comfort, $name], $arguments);
    }

    /**
     * Declare the value as being required
     *
     * @return $this
     */
    public function required()
    {
        $this->optional = false;

        $this->add(function ($value, $nameKey) {
            if (is_null($value)) {
                $this->createError('required', $value, $nameKey);
            }
        });


        return $this;
    }


    /**
     * Declare data is optional
     *
     * @return $this
     */
    public function optional()
    {
        $this->optional = true;

        return $this;
    }

    /**
     * Add adhoc validator to validation stack
     *
     * @param callable $validation
     * @return $this
     */
    public function add(callable $validation)
    {
        $this->validationStack[] = $validation;

        return $this;
    }

    /**
     * Validate given value matches any of the provided strings
     *
     * @param array $vals
     * @return $this
     */
    public function anyOf(array $vals)
    {
        $this->add(function ($value, $nameKey) use ($vals) {
            if (!in_array($value, $vals)) {
                return $this->createError('anyof', $value, $nameKey);
            }
        });

        return $this;
    }

    /**
     * On validation failure whether to return false or a validation error
     *
     * @param bool $val
     * @return $this
     */
    public function toBool($val = true)
    {
        $this->toBool = (boolean)$val;

        return $this;
    }

    /**
     * Set default value
     *
     * @param $value
     * @return $this
     */
    public function defaultValue($value)
    {
        $this->defaultValue = $value;

        return $this;
    }

    /**
     * Set error messages
     *
     * @param array $errorMessages
     * @return $this
     */
    public function errorMessages(array $errorMessages)
    {
        $errorMessages = array_map(function ($errorMessage) {
            if (is_string($errorMessage)) {
                $errorMessage = ['message' => $errorMessage];
            }

            return $errorMessage;
        }, $errorMessages);

        $this->errorHandlers = array_merge($this->errorHandlers, $errorMessages);
        return $this;
    }

    /**
     * Create an error with a formatted message
     *
     * @param string $key
     * @param null|string $value
     * @param null|string $valueKey
     * @param mixed $validationValue
     * @throws DiscomfortException
     * @throws ValidationException
     */
    protected function createError($key, $value = null, $valueKey = null, $validationValue = null)
    {
        if (!array_key_exists($key, $this->errorHandlers)) {
            throw new ValidationException(
                $key,
                $this->errorHandlers['default']['message']
            );
        }

        $errorHandler = $this->errorHandlers[$key];
        if (!array_key_exists('message_formatter', $errorHandler)) {
            $messageFormatter = function ($template, $value, $validationValue = null) {
                return sprintf($template, $value, $validationValue);
            };
        } else {
            $messageFormatter = $errorHandler['message_formatter'];
            if (!is_callable($messageFormatter)) {
                throw new DiscomfortException('"message_formatter" must be callable');
            }
        }

        if (!is_null($valueKey)) {
            $templateValue = $valueKey;
        } else {
            if (!is_string($value)) {
                $valueType = gettype($value);
                $templateValue = "'{$valueType}'";
            } else {
                $templateValue = "'{$value}'";
            }
        }

        $errorMessage = $messageFormatter(
            $errorHandler['message'],
            $templateValue ?: $errorHandler['value']
        );

        throw new ValidationException($key, $errorMessage);
    }
}
