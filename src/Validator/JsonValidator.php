<?php
namespace Comfort\Validator;

use Comfort\Comfort;

class JsonValidator extends AbstractValidator
{
    public function __construct(Comfort $comfort)
    {
        parent::__construct($comfort);

        $this->toBool(false);
    }


    public function keys(array $definition)
    {
        $this->add(function($value, $nameKey) use($definition) {
            $decodedValue = json_decode($value, true);
            if (json_last_error() != JSON_ERROR_NONE) {
                return $this->createError('json.invalid', $value, $nameKey);
            }

            $validation = $this->array()->keys($definition);
            return $validation($decodedValue);
        });

        return $this;
    }
}