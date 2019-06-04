<?php

namespace Wirecard\Converter;

use PHPUnit\Framework\TestCase;

class JsonConverterUTest extends TestCase
{
    private $converter;

    public function setUp()
    {
        $this->converter = new JsonConverter();
        $this->converter->setRegex(JsonConverter::ISO639_1);
        $json = '{"en": "English", "de": "German", "ar": "Arabic"}';
        $this->converter->mapJson($json);
    }

    public function testSetFallback()
    {
        $this->converter->setFallback('en');
        $this->assertEquals('en', $this->converter->getFallback());
    }

    /**
     * @expectedException  \InvalidArgumentException
     */
    public function testNonExistingSetFallback()
    {
        $this->converter->setFallback('mu');
    }

    /**
     * @expectedException  \InvalidArgumentException
     */
    public function testInvalidFormatSetFallback()
    {
        $this->converter->setFallback('muBU');
    }

    public function testSuccessValidateInput()
    {
        $result = $this->converter->validateInput('de');
        $this->assertTrue($result);
    }

    public function testFailureValidateInput()
    {
        $result = $this->converter->validateInput('ABCD');
        $this->assertFalse($result);
    }

    public function testSuccessConvert()
    {
        $result = $this->converter->convert('ar');
        $this->assertEquals('ar', $result);
    }

    /**
     * @expectedException  \InvalidArgumentException
     */
    public function testFailureConvert()
    {
        $result = $this->converter->convert('ABCD');
    }

    public function testRegex()
    {
        $this->converter->setRegex(JsonConverter::ISO639_1_ISO3166);
        $this->assertEquals(JsonConverter::ISO639_1_ISO3166, $this->converter->getRegex());
    }

    public function testSuccessReadJsonFile()
    {
        $result = $this->converter->readJsonFile(__DIR__ . "/assets/test-mapping.json");
        $this->assertContains('en', $result);
    }

    public function testFailureReadJsonFile()
    {
        $result = $this->converter->readJsonFile("nothing.json");
        $this->assertFalse($result);
    }

    public function testGetMapping()
    {
        $result = $this->converter->getMapping();
        $this->assertArrayHasKey('en', $result);
    }
}