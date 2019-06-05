# Wirecard IO converter

Includes various converters which are adaptable to your needs.

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c4e30e8daa32463fba81ff2673e4aaa3)](https://app.codacy.com/app/Wirecard/io-converter?utm_source=github.com&utm_medium=referral&utm_content=wirecard/io-converter&utm_campaign=Badge_Grade_Dashboard)
[![License](https://img.shields.io/badge/license-GPLv3-blue.svg)](https://github.com/wirecard/iso-wppv2-converter/blob/master/LICENSE)
[![PHP v5.6](https://img.shields.io/badge/php-v5.6-yellow.svg)](http://www.php.net)
[![PHP v7.0](https://img.shields.io/badge/php-v7.0-yellow.svg)](http://www.php.net)
[![PHP v7.1](https://img.shields.io/badge/php-v7.1-yellow.svg)](http://www.php.net)
[![PHP v7.2](https://img.shields.io/badge/php-v7.2-yellow.svg)](http://www.php.net)

## Installation

The library can be installed using [Composer](https://getcomposer.org/download/).
If you have not installed Composer, you can follow the [offical instructions](https://getcomposer.org/doc/00-intro.md).

Once composer is installed, run this in your terminal/command-line tool:

`composer require wirecard/io-converter`

## Wirecard WPP v2 Converter

WPP v2 converter can be used to convert country-language codes from [ISO-639-1](https://www.iso.org/iso-639-language-codes.html) and from [ISO-639-1](https://www.iso.org/iso-639-language-codes.html) combined with [ISO-3166](https://www.iso.org/iso-3166-country-codes.html) Alpha-2/Alpha-3 to WPP v2 supported language codes.

### Usage

In your application load the `vendor/autoload.php` that Composer provides.   
You can then initialize the `Converter` class like so:

```php
use Wirecard\Converter\WppVTwoConverter;

$converter = new WppVTwoConverter();
$converter->init();
```

This automatically loads all the supported language codes for WPP v2.

### Conversion

The ISO to WPP v2 converter does support two types of formats - ISO-639 and a mix with ISO-639 and ISO-3166. 

With ISO-639 input it returns a fallback language code if the code which was sent is not supported within WPP v2. 

*(The fallback language code is default set to "en")*

For the mixed language code it returns either the matching language code or the fallback language code.

To convert those codes to the correct format, you only have to pass the language code in one of the specified formats
and call the convert function: 

```php
$converter->convert("en-US");
// => "en"
```

```php
$converter->convert("de");
// => "de"
```

If you pass a correct formated language code which is not supported yet by WPP v2 you will get the fallback language code:

```php
$converter->convert("zz");
// => "en"
```

Furthermore there is the possibility to set your own fallback language to "de" for example with `$converter->setFallback('de')`.
Please ensure that the language code you want to set as fallback is supported by WPP v2, else the fallback setting will not work.

### Exceptions

Note that you have to send a valid language code format,
otherwise you will receive an `InvalidArgumentException`:

```php
$converter->convert("en-USUS");
// => InvalidArgumentException
```

The correct format for input language code can be `xx` or `xx-XX` or `xx-XXX`.
