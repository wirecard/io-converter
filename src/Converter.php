<?php

namespace Wirecard\IsoToWppvTo;

/**
 * Class Converter
 *
 * This class converts country-language codes (ISO-639 + ISO-3166 Alpha-2/Alpha-3) to WPPv2 supported language codes.
 * Language code input with ISO-639 and without ISO-3166 Alpha-2 can be processed too.
 * If the given language code is not supported fallback language code "en" will be returned during conversion.
 *
 * @package Wirecard\IsoToWppvTo
 * @since 1.0.0
 */
class Converter
{
    /** @var string formatmatch for ISO-639 */
    const ISO639 = "/^[a-z]{2,3}$/";

    /** @var string formatmatch ISO-639 || ISO-639 + ISO-3166 */
    const ISO_VALID = "/^[a-z]{2,3}(?:-[A-Z]{2,3})?$/";

    /** @var string formatmatch ISO-639 + ISO-3166 */
    const ISO_MIXED = "/^[a-z]{2,3}-[A-Z]{2,3}$/";

    /**
     * Supported language codes
     *
     * @var array
     * @since 1.0.0
     */
    private $languageCodes;

    /**
     * Default fallback language for unsupported language codes
     *
     * @var string
     * @since 1.0.0
     */
    private $fallbackCode;

    /**
     * Converter constructor.
     *
     * Loads WPPv2 supported language codes from given json file.
     */
    public function __construct()
    {
        $languageCodes = file_get_contents(__DIR__ . "/../assets/wpp-languagecodes.json");

        $this->languageCodes = json_decode($languageCodes, true);
        $this->fallbackCode = "en";
    }

    /**
     * Validates given language code and returns supported WPPv2 language code instead
     * For unsupported language codes within WPPv2 fallback language code will be returned
     *
     * @param $code
     * @return string
     * @since 1.0.0
     */
    public function convert($code)
    {
        if (!$this->isValidLanguageCode($code)) {
            throw new \InvalidArgumentException("Language code must be of format ISO-639 or mixed format with ISO-639 + ISO-3166 Alpha-2/Alpha-3.");
        }

        if ($this->includesCountryCode($code)) {
            $code = substr($code, 0, 2);
            if (!$code) {
                throw new \InvalidArgumentException("Language code must be of format ISO-639 or mixed format with ISO-639 + ISO-3166 Alpha-2/Alpha-3.");
            }
        }

        if (!array_key_exists($code, $this->languageCodes)) {
            return $this->fallbackCode;
        }

        return $code;
    }

    /**
     * Setter for valid fallbackcode supported by WPPv2
     *
     * @param $code
     * @since 1.0.0
     */
    public function setFallbackCode($code)
    {
        if (preg_match(self::ISO639, $code) && (array_key_exists($code, $this->languageCodes))) {
            $this->fallbackCode = $code;
        }
    }

    /**
     * Validated given language code for ISO-639 || ISO-639 + ISO-3166 Alpha-2/Alpha-3
     *
     * @param $code
     * @return bool
     * @since 1.0.0
     */
    private function isValidLanguageCode($code)
    {
        if (preg_match(self::ISO_VALID, $code)) {
            return true;
        }
        return false;
    }

    /**
     * Checks if the give language code includes a country code (ISO-3166 Alpha-2/Alpha-3)
     *
     * @param $code
     * @return bool
     * @since 1.0.0
     */
    private function includesCountryCode($code)
    {
        if (preg_match(self::ISO_MIXED, $code)) {
            return true;
        }
        return false;
    }
}