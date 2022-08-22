<?php

namespace App\DTO;

class AmountData
{
    public function __construct(private float|int $value, private int $precision)
    {
    }

    public function getValue(): float|int
    {
        return $this->value;
    }

    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function roundUp(): self
    {
        $pow         = pow(10, $this->precision);
        $this->value = (ceil($pow * $this->value) + ceil($pow * $this->value - ceil($pow * $this->value))) / $pow;

        return $this;
    }

    public function format():string
    {
       return number_format($this->value
            ,
            $this->precision,
            ".",
            ""
        );
    }
}
