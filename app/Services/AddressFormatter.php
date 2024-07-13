<?php

namespace App\Services;

class AddressFormatter
{
    /**
     * Format the address by concatenating postal code, country, and city.
     *
     * @param string $postalCode
     * @param string $country
     * @param string $city
     * @return string
     */
    public static function formatAddress($postalCode, $country, $city)
    {
        return implode(',', [$postalCode, $country, $city]);
    }
}
