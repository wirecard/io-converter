<?php

namespace Wirecard\IsoToWppvTo;

use PHPUnit\Framework\TestCase;

class ConverterUTest extends TestCase
{
    private $converter;

    public function setUp()
    {
        $this->converter = new Converter();
    }

    public function testLoadingOfSupportedLanguagesFromFile()
    {
        $reflection = new \ReflectionObject($this->converter);

        $property = $reflection->getProperty('languageCodes');
        $property->setAccessible(true);

        $this->assertArrayHasKey('de', $property->getValue($this->converter));
    }

    public function testConversionWithIso639()
    {
        $supportedCode = $this->converter->convert('ar');
        $this->assertEquals('ar', $supportedCode);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConversionWithInvalidCode()
    {
        $supportedCode = $this->converter->convert('abc-DEFGHIJ');
    }

    public function testConversionWithMixedIso()
    {
        $supportedCode = $this->converter->convert('de-DE');
        $this->assertEquals('de', $supportedCode);
    }

    /**
     * @expectedException  \InvalidArgumentException
     */
    public function testConversionWithoutLanguageCode()
    {
        $supportedCode = $this->converter->convert('');
    }

    public function testConversionWithFallback()
    {
        $supportedCode = $this->converter->convert('at');
        $this->assertEquals('en', $supportedCode);
    }

    public function testSetValidFallback()
    {
        $fallback = 'de';
        $this->converter->setFallbackCode($fallback);

        $this->assertEquals($fallback, $this->converter->getFallbackCode());
    }
}
