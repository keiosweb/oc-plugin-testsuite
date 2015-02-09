# Keios/oc-plugin-testsuite

[![Latest Version](https://img.shields.io/github/release/keiosweb/moneyright.svg?style=flat-square)](https://github.com/keiosweb/moneyright/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/keiosweb/moneyright.svg?style=flat-square)](https://packagist.org/packages/keiosweb/moneyright)

Testing middleware package: provides extended PHPUnit's TestCase class - OctoberPluginTestCase, 
which allows for unit testing in OctoberCMS / Laravel context and loads all related composer autoloaders.

## Requirements
OctoberCMS
PHPUnit

## Install

Via Composer

``` bash
$ composer require keiosweb/oc-plugin-testsuite --dev
```

## Usage

Plugin's phpunit.xml should look somewhat like this:

``` xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit bootstrap="vendor/keios/oc-plugin-testsuite/bootstrap/autoload.php"
         backupGlobals="false"
         backupStaticAttributes="false"
         colors="true"
         verbose="true"
         convertErrorsToExceptions="true"
         convertNoticesToExceptions="true"
         convertWarningsToExceptions="true"
         processIsolation="false"
         stopOnFailure="false">
         syntaxCheck="false"
    <testsuites>
        <testsuite name="October Plugin Unit Test Suite">
            <directory>tests/unit</directory>
        </testsuite>
        <testsuite name="October Plugin Functional Test Suite">
            <directory>tests/functional</directory>
        </testsuite>
    </testsuites>
    <logging>
        <log type="tap" target="build/report.tap"/>
        <log type="junit" target="build/report.junit.xml"/>
        <log type="coverage-html" target="build/coverage" charset="UTF-8" yui="true" highlight="true"/>
        <log type="coverage-text" target="build/coverage.txt"/>
        <log type="coverage-clover" target="build/logs/clover.xml"/>
    </logging>
</phpunit>

```

## Credits

- [Keios Solutions](https://github.com/keiosweb)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
