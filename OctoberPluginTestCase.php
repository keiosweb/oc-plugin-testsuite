<?php

/**
 * Class OctoberPluginTestCase
 */
abstract class OctoberPluginTestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * @var array
     */
    protected $refreshPlugins = [];

    /**
     * @var bool
     */
    protected $requiresOctoberMigration = false;


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

    /**
     * Perform test case set up
     *
     * @deprecated use pluginTestSetUp method
     */
    public final function setUp()
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
         * If test maker wants to create functional test, migrate october and given plugins
         */

        if (count($this->refreshPlugins) > 0) {
            foreach ($this->refreshPlugins as $pluginCode) {
                $this->refreshPlugin($pluginCode);
            }
        }

        /*
         * If test maker wants october environment for some reason, migrate october
         */
        if ((count($this->refreshPlugins) === 0) && $this->requiresOctoberMigration) {
            $this->migrateOctober();
        }

        /*
         * Prevent mailer from actually sending emails
         */
        Mail::pretend(true);

        $this->pluginTestSetUp();
    }

    /**
     * Alternative test case boot method
     */
    public function pluginTestSetUp()
    {
    }

    /**
     * Clean up after tests
     */
    public function tearDown()
    {
        $this->rebootAllModels();
        parent::tearDown();
        unset ($this->app);
    }

    /**
     * Boot given plugin in OctoberCMS environment
     */
    protected final function refreshPlugin($code)
    {
        $this->assertPluginCode($code);

        $path = $this->createPluginPath($code);
        $namespace = $this->createNamespace($code);

        $this->migrateOctober();

        \System\Classes\PluginManager::instance()->loadPlugin(
            $namespace,
            $path
        );

        Artisan::call('plugin:refresh', ['name' => $code]);
    }

    /**
     * Clears event listeners of available plugin models and boots them
     */
    protected final function rebootAllModels()
    {
        foreach (get_declared_classes() as $class) {
            /*
             * Reboot event listeners on all October\Rain\Database\Model subclasses
             * with exception of Pivot model that is a special, internal model for pivot tables
             * and should not be initialised outside of relation context.
             */
            if (is_subclass_of($class, 'October\Rain\Database\Model') && $class !== 'October\Rain\Database\Pivot') {
                $class::flushEventListeners();
            }
        }

        \October\Rain\Database\Model::flushEventListeners();
    }

    /**
     * Call october:up command to migrate whole OctoberCMS to in-memory SQLite Database
     */
    protected final function migrateOctober()
    {
        Artisan::call('october:up');
    }

    /**
     * Check that given plugin code is valid
     *
     * @param string $code
     *
     * @throws \RuntimeException
     */
    protected final function assertPluginCode($code)
    {
        if (!preg_match('/[A-Za-z0-9-_]*\.[A-Za-z0-9-_]*/', $code)) {
            throw new \RuntimeException(
                'Plugin name '.$code.' is invalid - should follow vendor.pluginName schema.'
            );
        }
    }

    /**
     * Creates full plugin path from plugin code
     *
     * @param string $code
     *
     * @returns string
     */
    protected final function createPluginPath($code)
    {
        $parts = explode('.', $code);

        $parts = array_map(
            function ($part) {
                return strtolower($part);
            },
            $parts
        );

        list($vendor, $plugin) = $parts;

        return base_path().\Config::get('cms.pluginsPath').'/'.$vendor.'/'.$plugin;
    }

    /**
     * Creates namespace from plugin code
     *
     * @param string $code
     *
     * @returns string
     */
    protected final function createNamespace($code)
    {
        $parts = explode('.', $code);

        $parts = array_map(
            function ($part) {
                return ucfirst(strtolower($part));
            },
            $parts
        );

        list($vendor, $plugin) = $parts;

        return '\\'.$vendor.'\\'.$plugin;
    }
}
