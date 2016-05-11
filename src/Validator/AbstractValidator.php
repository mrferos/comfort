<?php
namespace Comfort\Validator;

use Comfort\Comfort;
use Comfort\Error;
use Comfort\Exception\DiscomfortException;
use Comfort\Exception\ValidationException;
use Comfort\ValidationError;

/**
 * Class AbstractValidator
 * @package Comfort\Validator
 */
abstract class AbstractValidator
{
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
    private $toBool = true;
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

    public function __invoke($value, $key = null)
    {
        try {
            foreach ($this->validationStack as $validator) {
                $retVal = $validator($value, $key);
                $value = $retVal === null ? $value : $retVal;
            }

            return $value;
        }catch(ValidationException $validationException) {
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

    public function required()
    {
        $this->add(function($value) {
            if (is_null($value)) {
                return $this->createError('required', $value);
            }
        });

        return $this;
    }

    public function defaultValue($value)
    {

    }

    protected function add(callable $validation)
    {
        $this->validationStack[] = $validation;
    }

    public function toBool($val = true)
    {
        $this->toBool = (boolean)$val;

        return $this;
    }

    protected function createError($key, $value = null, $valueKey = null)
    {
        if (!array_key_exists($key, $this->errorHandlers)) {
            throw new ValidationException(
                $key,
                $this->errorHandlers['default']['message']
            );
        }

        $errorHandler = $this->errorHandlers[$key];
        if (!array_key_exists('message_formatter', $errorHandler)) {
            $messageFormatter = function($template, $value) {
                return sprintf($template, $value);
            };
        } else {
            $messageFormatter = $errorHandler['message_formatter'];
            if (!is_callable($messageFormatter)) {
                throw new DiscomfortException('"message_formatter" must be callable');
            }
        }

        $templateValue = "'{$value}'";
        if (!is_null($valueKey)) {
            $templateValue = $valueKey;
        }

        $errorMessage = $messageFormatter(
            $errorHandler['message'],
            $templateValue ?: $errorHandler['value']
        );

        throw new ValidationException($key, $errorMessage);
    }
}