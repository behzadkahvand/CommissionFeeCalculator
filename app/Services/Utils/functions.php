<?php

if (!function_exists('findCurrencyPrecision')) {
    function findCurrencyPrecision(string $value): int
    {
        $precision = 0;
        $decimal   = explode(".", $value);
        if (isset($decimal[1])) {
            $precision = strlen($decimal[1]);
        }

        return $precision;
    }
}

