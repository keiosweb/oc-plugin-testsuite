<?php

class OctoberPluginTestCase extends Illuminate\Foundation\Testing\TestCase
{

    /**
     * Creates the application.
     *
     * @return Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        // __DIR__ = base_path() . '/plugins/vendor/plugin/vendor/keios/oc-plugin-testsuite

        $result = require __DIR__ . '/../../../../../../bootstrap/app.php';

        /*
         * Use the array driver during the unit testing
         */
        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => ''
        ]);

        App::setLocale('en');

        return $result;
    }

    public function setUp(){
        parent::setUp();

        Artisan::call('october:up');
        Mail::pretend(true);
    }

}
