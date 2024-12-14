<?php

namespace App\Console\Commands;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Illuminate\Console\Command;

class DoctrineDatabaseInspect extends Command
{
    protected $signature = 'doctrine:inspect';
    protected $description = 'Inspect database schema using Doctrine';

    public function handle(EntityManager $em)
    {
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($em);
        
        $sql = $schemaTool->getCreateSchemaSql($metadatas);
        foreach ($sql as $query) {
            $this->info($query);
        }
    }
} 