<?php
namespace Comfort\Validator;

use Comfort\Comfort;
use Comfort\Exception\ValidationException;
use Comfort\ValidationError;

class ArrayValidator extends AbstractValidator
{
    /**
     * ArrayValidator constructor.
     * @param Comfort $comfort
     */
    public function __construct(Comfort $comfort)
    {
        parent::__construct($comfort);

        $this->toBool(false);

        $this->add(function ($value, $nameKey) {
            if (!is_array($value)) {
                return $this->createError('array.not_array', $value, $nameKey);
            }
        });

        $this->errorHandlers += [
            'array.min' => [
                'message' => '%s must contains at least %s elements'
            ],
            'array.max' => [
                'message' => '%s must contain no more than %s elements'
            ],
            'array.length' => [
                'message' => '%s must only contain %s elements'
            ],
            'array.unique' => [
                'message' => '%s is not an array containing all unique values'
            ],
            'array.items.validation_failure' => [
                'message' => '%s has an item that fails validation'
            ],
            'array.not_array' => [
                'message' => '%s is not an array'
            ],
        ];
    }

    /**
     * Given a set of keys apply the specified validation rules
     *
     * @param array $definition
     * @return $this
     */
    public function keys(array $definition)
    {
        return $this->add(function (&$value) use ($definition) {
            /**
             * @var string $key
             * @var AbstractValidator $validator
             */
            foreach ($definition as $key => $validator) {
                if (!$validator instanceof AbstractValidator) {
                    return $this->createError('array.invalid_keys_entry');
                }

                $validator->toBool(false);
                $validatorValue = isset($value[$key]) ? $value[$key] : null;
                $result = $validator($validatorValue, $key);
                if ($result instanceof ValidationError) {
                    throw new ValidationException($result->getKey(), $result->getMessage());
                }

                if (isset($value[$key])) {
                    $value[$key] = $result;
                }
            }

            return $value;
        });
    }

    /**
     * Validate array has more than $min elements
     *
     * @param $min
     * @return $this
     */
    public function min($min)
    {
        return $this->add(function ($value, $nameKey) use ($min) {
            if (count($value) < $min) {
                return $this->createError('array.min', $value, $nameKey);
            }
        });
    }

    /**
     * Validate array has only up to $max characters
     *
     * @param $max
     * @return $this
     */
    public function max($max)
    {
        return $this->add(function ($value, $nameKey) use ($max) {
            if (count($value) > $max) {
                return $this->createError('array.max', $value, $nameKey);
            }
        });
    }

    /**
     * Validate array contains exactly $length elements
     *
     * @param $length
     * @return $this
     */
    public function length($length)
    {
        return $this->add(function ($value, $nameKey) use ($length) {
            if (count($value) != $length) {
                return $this->createError('array.length', $value, $nameKey);
            }
        });
    }

    /**
     * Validate array is unique
     *
     * @return $this
     */
    public function unique()
    {
        return $this->add(function ($value, $nameKey) {
            if (md5(serialize($value)) != md5(serialize(array_unique($value)))) {
                return $this->createError('array.unique', $value, $nameKey);
            }
        });
    }

    /**
     * Apply $definition to individual items in the array,
     * used in case of multi-dimensional array
     *
     * @param AbstractValidator $definition
     * @return $this
     */
    public function items(AbstractValidator $definition)
    {
        return $this->add(function ($value, $nameKey) use ($definition) {
            foreach ($value as $key => $val) {
                $resp = $definition($val, $nameKey);
                if ($resp instanceof ValidationError) {
                    return $resp;
                }
            }
        });

        return $this;
    }
}
