<?php

namespace Wirecard\Converter;

/**
 * Class JsonConverter
 *
 * Base methods for mapping input with given mapping files in json
 *
 * @package Wirecard\Converter
 * @since 1.0.0
 */
class JsonConverter implements ConverterInterface
{
    /** @var string formatmatch for ISO-639-1 */
    const ISO639_1 = "/^[a-z]{2}$/";

    /** @var string formatmatch ISO-639-1 - ISO-3166-1 */
    const ISO639_1_ISO3166 = "/^[a-z]{2}-[A-Z]{2,3}$/";

    /** @var string basic json validation regex */
    const VALID_JSON = "/{.*:(?:{.*:)?.*}(?:})?$/";

    /**
     * Supported mapping
     *
     * @var array
     * @since 1.0.0
     */
    protected $mapping;

    /**
     * Default fallback for unsupported mapping
     *
     * @var string
     * @since 1.0.0
     */
    protected $fallback;

    /**
     * Validation regex
     *
     * @var string
     * @since 1.0.0
     */
    protected $regex;

    /**
     * JsonConverter constructor.
     *
     * Sets default json validation regex
     */
    public function __construct()
    {
        $this->regex = self::VALID_JSON;
    }

    /**
     * Sets valid fallback case for specific conversion
     *
     * @param $fallback
     * @throws \InvalidArgumentException
     */
    public function setFallback($fallback)
    {
        if (!preg_match($this->regex, $fallback) || (!array_key_exists($fallback, $this->mapping))) {
            throw new \InvalidArgumentException(
                "The input fallback is invalid."
            );
        }
        $this->fallback = $fallback;
    }

    /**
     * Returns setted fallback for specific conversion
     *
     * @return mixed
     */
    public function getFallback()
    {
        return $this->fallback;
    }

    /**
     * Converts formatted input to new formatted output
     *
     * @param $input
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function convert($input)
    {
        if (!$this->validateInput($input)) {
            throw new \InvalidArgumentException(
                "Your input format is invalid. Please check again."
            );
        }

        return $input;
    }

    /**
     * Validates input with given format
     *
     * @param $input
     * @return bool
     */
    public function validateInput($input)
    {
        if (preg_match($this->regex, $input)) {
            return true;
        }
        return false;
    }

    /**
     * Set format to be used for validation and conversion
     *
     * @param $regex
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;
    }

    /**
     * Get regex format which is used for validation and conversion
     *
     * @return mixed|string
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * Reads from file path and return json string
     *
     * @param $filePath
     * @return string
     */
    public function readJsonFile($filePath)
    {
        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
        return false;
    }

    /**
     * Maps given json string to $mapping array
     *
     * @param $input
     */
    public function mapJson($input)
    {
        $this->mapping= json_decode($input, true);
    }

    /**
     * Get mapped values for conversion
     *
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }
}
