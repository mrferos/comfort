<?php
namespace Comfort\Validator;

class DateValidator extends AbstractValidator
{
    /**
     * Specify format date must be in
     *
     * @param string $format
     * @return $this
     */
    public function format($format)
    {
        return $this->add(function($value, $namekey) use($format) {
            $date = \DateTime::createFromFormat($format, $value);
            if (false === $date) {
                return $this->createError('date.format', $value, $namekey);
            }
        });
    }

    /**
     * Specify min date
     *
     * @param $date
     * @return $this
     */
    public function min($date)
    {
        return $this->add(function($value, $nameKey) use ($date) {
            $minDate = strtotime($date);
            $curDate = strtotime($value);

            if ($curDate < $minDate) {
                return $this->createError('date.min', $value, $nameKey);
            }
        });
    }

    /**
     * Specify max date
     *
     * @param $date
     * @return $this
     */
    public function max($date)
    {
        return $this->add(function($value, $nameKey) use ($date) {
            $maxDate = strtotime($date);
            $curDate = strtotime($value);

            if ($curDate > $maxDate) {
                return $this->createError('date.max', $value, $nameKey);
            }
        });
    }

    /**
     * Date must be a unix timestamp
     *
     * @return $this
     */
    public function timestamp()
    {
        return $this->add(function ($value, $nameKey) {
            try {
                new \DateTime('@' . $value);
            }catch(\Exception $e) {
                return $this->createError('date.timestamp', $value, $nameKey);
            }
        });
    }


    /**
     * Date must be in ISO format
     *
     * @return $this
     */
    public function iso()
    {
        return $this->format(DATE_ISO8601);
    }
}