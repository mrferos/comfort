<?php
namespace Comfort\Validator;

use Comfort\Comfort;

/**
 * Class JsonValidator
 * @package Comfort\Validator
 * @method $this array()
 */
class JsonValidator extends AbstractValidator
{
    public function __construct(Comfort $comfort)
    {
        parent::__construct($comfort);

        $this->toBool(false);

        $this->add(function($value, $nameKey) {
            json_decode($value);
            if (json_last_error() != JSON_ERROR_NONE) {
                return $this->createError('json.invalid', $value, $nameKey);
            }
        });
    }

    /**
     * Apply rules to a given schema
     *
     * @param array $definition
     * @return $this
     */
    public function keys(array $definition)
    {
        return $this->add(function($value, $nameKey) use($definition) {
            $decodedValue = json_decode($value, true);
            $validation = $this->array()->keys($definition);
            return $validation($decodedValue);
        });
    }
}