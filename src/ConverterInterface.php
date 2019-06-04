<?php

namespace Wirecard\Converter;

/**
 * Converter interface for converting input format to specific output format
 *
 * @package Wirecard\Converter
 */
interface ConverterInterface
{
    const VERSION = '1.0.0';

    /**
     * Sets valid fallback case for specific conversion
     *
     * @param $fallback
     */
    public function setFallback($fallback);

    /**
     * Returns setted fallback for specific conversion
     *
     * @return mixed
     */
    public function getFallback();

    /**
     * Converts input to new formatted output
     *
     * @param $input
     * @return mixed
     */
    public function convert($input);

    /**
     * Validates input with predefined conversion format
     *
     * @param $input
     * @return bool
     */
    public function validateInput($input);

    /**
     * Set format regex to be used for validation and conversion
     *
     * @param $regex
     */
    public function setRegex($regex);

    /**
     * Get format regex for validation
     *
     * @return mixed
     */
    public function getRegex();
}
