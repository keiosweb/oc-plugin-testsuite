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
        /*
         * Build application instance, bootstap it
         */

        // __DIR__ = base_path() . '/plugins/vendor/plugin/vendor/keios/oc-plugin-testsuite

        $app = require __DIR__.'/../../../../../../bootstrap/app.php';

        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

        $app->setLocale('en');

        /*
         * Use the sqlite memory driver during the unit testing
         */
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set(
            'database.connections.sqlite',
            [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => ''
            ]
        );

        return $app;
    }

    public function setUp()
    {
        /*
         * Create application instance
         */
        parent::setUp();

        /*
         * Reboot October Singletons' dependencies
         */
        \System\Classes\PluginManager::instance()->bindContainerObjects();
        \System\Classes\UpdateManager::instance()->bindContainerObjects();

        /*
         * Migrate October to RAM sqlite database
         */
        Artisan::call('october:up');

        /*
         * Prevent mailer from actually sending emails
         */
        Mail::pretend(true);
    }


    public function tearDown()
    {
        parent::tearDown();
        unset ($this->app);
    }

}
