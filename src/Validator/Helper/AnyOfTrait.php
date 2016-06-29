<?php
namespace Comfort\Validator\Helper;

trait AnyOfTrait
{
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
}
