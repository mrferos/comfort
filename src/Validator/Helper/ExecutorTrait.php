<?php
namespace Comfort\Validator\Helper;

use Comfort\Exception\ValidationException;
use Comfort\ValidationError;

trait ExecutorTrait
{
    /**
     * Execute the validation stack and fail on first
     *
     * @param $value
     * @param null $key
     * @return bool|ValidationError|null
     */
    protected function validate($value, $key = null)
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
}