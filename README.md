# Keios/oc-plugin-testsuite

[![Latest 
Version](https://img.shields.io/github/release/keiosweb/oc-plugin-testsuite.svg?style=flat-square)](https://github.com/keiosweb/oc-plugin-testsuite/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/keios/oc-plugin-testsuite.svg?style=flat-square)](https://packagist.org/packages/keios/oc-plugin-testsuite)

Testing middleware package: provides extended PHPUnit's TestCase class - OctoberPluginTestCase, 
which allows for unit testing in OctoberCMS / Laravel context and loads all related composer autoloaders.

Prepared for OctoberCMS Release Candidate, won't work with beta releases.

## Requirements
OctoberCMS

## Install

Via Composer

``` bash
$ composer require keios/oc-plugin-testsuite dev-master --dev
```

## Usage

#### phpunit.xml

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
         stopOnFailure="false"
         syntaxCheck="false">
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
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="SESSION_DRIVER" value="array"/>
    </php>
</phpunit>

```

#### Test Cases

Package provides class OctoberPluginTestCase, which is prepared to run tests in Laravel 5 / OctoberCMS environment and comes with handy features like automatic migrations.

Your test cases should follow these rules:

**basic unit test**
``` php
<?php namespace Vendor\PluginName\Tests

class SomeClassTest extends \OctoberPluginTestCase {

    protected $someMockObjectUsedInAllTests;

    public function pluginTestSetUp () // PHPUnit's set up method is unavailable, use this instead
    {
        $this->someMockObjectUsedInAllTests = \Mockery::mock('Some\Other\Class');
    }

    public function testSomething() { 
        $instance = new SomeClass($this->someMockObjectUsedInAllTests);
        
        $this->assertEquals('result', $instance->getResult());
    }

}
``` 

**functional test requiring October environment, but not refreshing plugins**
``` php
<?php namespace Vendor\PluginName\Tests

class AFunctionalTest extends \OctoberPluginTestCase {

    protected $requiresOctoberMigration = true; // test suite will migrate october

}
```

**functional test requiring October environment AND refreshing plugin 'Vendor.PluginName'**
``` php
<?php namespace Vendor\PluginName\Tests

class AFunctionalTestRequiringMigrations extends \OctoberPluginTestCase {

    /*
     * Here you can add multiple plugin codes, ie.: your plugin and it's dependencies
     */
    protected $refreshPlugins = ['Vendor.PluginName', 'OtherVendor.OtherPlugin'];

}
```

## Credits

- [Keios Solutions](https://github.com/keiosweb)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
