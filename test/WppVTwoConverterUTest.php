<?php

namespace Wirecard\Converter;

use PHPUnit\Framework\TestCase;

class WppVTwoConverterUTest extends TestCase
{
    private $converter;

    public function setUp()
    {
        $this->converter = new WppVTwoConverter();
        $this->converter->init();
    }

    public function testLoadingOfSupportedLanguagesFromFile()
    {
        $reflection = new \ReflectionObject($this->converter);

        $property = $reflection->getProperty('mapping');
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

    public function testSetFallback()
    {
        $fallback = 'de';
        $this->converter->setFallback($fallback);

        $this->assertEquals($fallback, $this->converter->getFallback());
    }

    /**
     * @expectedException  \InvalidArgumentException
     */
    public function testFailureSetFallback()
    {
        $fallback = 'ABCD';
        $this->converter->setFallback($fallback);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFailureInit()
    {
        $fallbackFile = 'nothing.php';
        $this->converter->init($fallbackFile);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFailureJsonInit()
    {
        $fallbackFile = __DIR__ . "/assets/not-json.json";
        $this->converter->init($fallbackFile);
    }
}
