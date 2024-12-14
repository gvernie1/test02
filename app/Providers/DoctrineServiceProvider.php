<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\DBAL\DriverManager;

class DoctrineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EntityManager::class, function ($app) {
            $paths = [base_path('app/Entities')];
            $isDevMode = config('app.debug');

            // Create the configuration
            $config = ORMSetup::createAttributeMetadataConfiguration(
                $paths,
                $isDevMode
            );

            // Database configuration parameters
            $connectionParams = [
                'driver'   => 'pdo_mysql',
                'host'     => config('database.connections.mysql.host'),      // Gets DB_HOST
                'dbname'   => config('database.connections.mysql.database'),  // Gets DB_DATABASE
                'user'     => config('database.connections.mysql.username'),  // Gets DB_USERNAME
                'password' => config('database.connections.mysql.password'),  // Gets DB_PASSWORD
                'charset'  => config('database.connections.mysql.charset'),
            ];

            // Create the EntityManager
            $connection = DriverManager::getConnection($connectionParams, $config);
            return new EntityManager($connection, $config);
        });
    }
} 