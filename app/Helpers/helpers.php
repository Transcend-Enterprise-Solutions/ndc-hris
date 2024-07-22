<?php

if (!function_exists('currency_format')) {
    /**
     * Format a number as currency with the ₱ sign, two decimal places, and comma separators.
     *
     * @param float $number
     * @return string
     */
    function currency_format($number) {
        return '₱ ' . number_format((float)$number, 2, '.', ',');
    }
}
