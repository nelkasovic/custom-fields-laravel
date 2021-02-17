<?php

namespace Wimando\Tests;

use Wimando\LaravelCustomFields\LaravelCustomFieldsServiceProvider;
use Wimando\Tests\Support\Survey;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/Support/Factories');
        $this->setUpDatabase($this->app);
        $this->withoutExceptionHandling();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelCustomFieldsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });

        $this->prepareDatabaseForHasCustomFieldsModel();
        $this->runMigrationStub();
    }

    protected function runMigrationStub()
    {
        include_once __DIR__ . '/../database/migrations/create_custom_fields_tables.php.stub';
        (new \CreateCustomFieldsTables())->up();
    }

    protected function prepareDatabaseForHasCustomFieldsModel()
    {
        include_once __DIR__ . '/../tests/support/migrations/create_surveys_and_survey_responses_tables.php';
        (new \CreateSurveysAndSurveyResponsesTables())->up();
    }

    protected function resetDatabase()
    {
        $this->artisan('migrate:fresh');
        $this->runMigrationStub();
    }
}
