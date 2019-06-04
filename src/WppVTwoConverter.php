<?php

namespace Wirecard\Converter;

/**
 * Class WppVTwoConverter
 *
 * This class converts country-language codes (ISO-639-1 + ISO-3166-1 Alpha-2/Alpha-3)
 * to WPPv2 supported language codes.
 * Language code input with ISO-639-1 and without ISO-3166-1 Alpha-2 can be processed too.
 * If the given language code is not supported fallback language code "en" will be returned during conversion.
 *
 * @package Wirecard\Converter
 * @since 1.0.0
 */
class WppVTwoConverter extends JsonConverter
{
    /** @var string formatmatch ISO-639-1 || ISO-639-1 + ISO-3166-1 */
    const ISO_VALID = "/^[a-z]{2}(?:-[A-Z]{2,3})?$/";

    /** @var string fallback language mapping file */
    const FALLBACK_FILE = __DIR__ . "/../assets/wpp-languagecodes.json";

    /**
     * WppVTwoConverter constructor.
     *
     * Sets default values for conversion
     */
    public function __construct()
    {
        parent::__construct();

        $this->fallback = 'en';
        $this->mapping = array('en' => 'English');
        $this->regex = self::ISO_VALID;
    }

    /**
     * Initialize Wppv2 converter with default values
     *
     * Loads WPPv2 supported language codes from given json file, sets fallback language and validation regex.
     *
     * @param string
     * @throws \InvalidArgumentException
     */
    public function init($jsonFile = '')
    {
        if (empty($jsonFile)) {
            $jsonFile = self::FALLBACK_FILE;
        }
        $json = $this->readJsonFile($jsonFile);
        if (!$json) {
            throw new \InvalidArgumentException(
                "Fallback file can not be found. Please ensure that the desired file exists."
            );
        }
        $this->mapJson($json);
        if (empty($this->getMapping())) {
            throw new \InvalidArgumentException(
                "The fallback file for mapping is invalid. Please check its content."
            );
        }

        $this->setRegex(self::ISO_VALID);
        $this->setFallback('en');
    }

    /**
     * Sets valid fallback case for specific conversion
     *
     * @param $fallback
     * @throws \InvalidArgumentException
     */
    public function setFallback($fallback)
    {
        if (!preg_match(self::ISO639_1, $fallback) || (!array_key_exists($fallback, $this->getMapping()))) {
            throw new \InvalidArgumentException(
                "The input fallback is invalid. Please check again."
            );
        }
        $this->fallback = $fallback;
    }

    /**
     * Validates given language code and returns supported WPPv2 language code instead
     * For unsupported language codes within WPPv2 fallback language code will be returned
     *
     * @param $input
     * @return string
     * @throws \InvalidArgumentException
     * @since 1.0.0
     */
    public function convert($input)
    {
        if (!$this->validateInput($input)) {
            throw new \InvalidArgumentException(
                "Language code must be of format ISO-639-1 or mixed format with ISO-639-1 + ISO-3166 Alpha-2/Alpha-3."
            );
        }

        if ($this->includesCountryCode($input)) {
            $input = mb_substr($input, 0, 2);
        }

        if (!array_key_exists($input, $this->getMapping())) {
            return $this->getFallback();
        }

        return $input;
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
        if (preg_match(self::ISO639_1_ISO3166, $code)) {
            return true;
        }
        return false;
    }
}
