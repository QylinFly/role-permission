<?php

namespace Qylinfly\Permission\Test;

use Qylinfly\Permission\Contracts\Role;
use Illuminate\Database\Schema\Blueprint;
use Qylinfly\Permission\PermissionRegistrar;
use Qylinfly\Permission\Contracts\Permission;
use Orchestra\Testbench\TestCase as Orchestra;
use Qylinfly\Permission\PermissionServiceProvider;

abstract class TestCase extends Orchestra
{
    /**
     * @var \Qylinfly\Permission\Test\User
     */
    protected $testUser;

    /**
     * @var \Qylinfly\Permission\Models\Role
     */
    protected $testRole;

    /**
     * @var \Qylinfly\Permission\Models\Permission
     */
    protected $testPermission;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase($this->app);

        $this->reloadPermissions();

        $this->testUser = User::first();
        $this->testRole = app(Role::class)->first();
        $this->testPermission = app(Permission::class)->find(1);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            PermissionServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('view.paths', [__DIR__.'/resources/views']);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
        });

        include_once __DIR__.'/../resources/migrations/create_permission_tables.php.stub';

        (new \CreatePermissionTables())->up();

        User::create(['email' => 'test@user.com']);
        $app[Role::class]->create(['name' => 'testRole']);
        $app[Role::class]->create(['name' => 'testRole2']);
        $app[Permission::class]->create(['name' => 'edit-articles']);
        $app[Permission::class]->create(['name' => 'edit-news']);
    }

    /**
     * Reload the permissions.
     *
     * @return bool
     */
    protected function reloadPermissions()
    {
        return app(PermissionRegistrar::class)->registerPermissions();
    }

    /**
     * Refresh the testuser.
     */
    public function refreshTestUser()
    {
        $this->testUser = $this->testUser->fresh();
    }
}
