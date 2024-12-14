<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

require 'vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/bootstrap/app.php';

$entityManager = $app->get(EntityManager::class);

return ConsoleRunner::createApplication(
    new SingleManagerProvider($entityManager)
); 