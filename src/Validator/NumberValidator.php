<?php
namespace Comfort\Validator;

use Comfort\Comfort;

class NumberValidator extends AbstractValidator
{
    public function __construct(Comfort $comfort)
    {
        parent::__construct($comfort);

        $this->toBool(false);

        $this->errorHandlers += [
            'number.min' => [
                'message' => '%s must be above %s'
            ],
            'number.max' => [
                'message' => '%s must be below %s'
            ],
            'number.precision' => [
                'message' => '%s must be precision %s'
            ],
            'number.is_int' => [
                'message' => '%s must be an int'
            ],
            'number.is_float' => [
                'message' => '%s must be a float'
            ],
            'number.is_number' => [
                'message' => '%s must be a number'
            ],
        ];
    }

    /**
     * Validate number is no less than $min
     *
     * @param $min
     * @return $this
     */
    public function min($min)
    {
        return $this->add(function ($value, $nameKey) use ($min) {
            if ($value < $min) {
                return $this->createError('number.min', $value, $nameKey);
            }
        });
    }

    /**
     * Validate number is no more than $max
     *
     * @param $max
     * @return $this
     */
    public function max($max)
    {
        return $this->add(function ($value, $nameKey) use ($max) {
            if ($value > $max) {
                return $this->createError('number.max', $value, $nameKey);
            }
        });
    }

    /**
     * Validate precision of number is $precision
     *
     * @todo think if less kludgy way to do this...
     * @param $precision
     * @return $this
     */
    public function precision($precision)
    {
        return $this->add(function ($value, $nameKey) use ($precision) {
            if (strlen(substr($value, strpos($value, '.') + 1)) != $precision) {
                return $this->createError('number.precision', $value, $nameKey);
            }
        });
    }

    /**
     * Validate value os an integer
     *
     * @return $this
     */
    public function isInt()
    {
        return $this->add(function ($value, $nameKey) {
            if (!is_int($value)) {
                return $this->createError('number.is_int', $value, $nameKey);
            }
        });
    }

    /**
     * Validate value is a float
     *
     * @return $this
     */
    public function isFloat()
    {
        return $this->add(function ($value, $nameKey) {
            if (!is_float($value)) {
                return $this->createError('number.is_float', $value, $nameKey);
            }
        });
    }

    /**
     * Validate value is numeric, be it float or int.
     *
     * @return $this
     */
    public function isNumber()
    {
        return $this->add(function ($value, $nameKey) {
            if (!is_numeric($value)) {
                return $this->createError('number.is_numeric', $value, $nameKey);
            }
        });
    }
}
