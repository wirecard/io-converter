<?php

namespace Wirecard\IsoToWppvTo;

/**
 * Class Converter
 *
 * This class converts country-language codes (ISO-639 + ISO-3166 Alpha-2) to WPPv2 supported language codes.
 * Language code input with ISO-639 and without ISO-3166 Alpha-2 can be processed too.
 * If the given language code is not supported fallback language code "en" will be returned during conversion.
 *
 * @package Wirecard\IsoToWppvTo
 * @since 1.0.0
 */
class Converter
{
    private $languageCodes;

    /**
     * Converter constructor.
     *
     * Loads WPPv2 supported language codes from given json file.
     */
    public function __construct()
    {
        $this->languageCodes = file_get_contents(__DIR__ . "/../assets/wpp-languagecodes.json");
    }
}