<?php

namespace Tests\System;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;

class DatabaseMigrationsAndSeedersTest extends TestCase
{

  use DatabaseMigrations;

  public function test_database_migrations_and_seeders_work(): void
  {
    $this->runDatabaseMigrations();
    (new DatabaseSeeder())->run();
    self::assertTrue(true);
  }

}
