<?php

namespace App\Console\Commands;

use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class TestDoctrine extends Command
{
    protected $signature = 'doctrine:test';
    protected $description = 'Test Doctrine setup';

    public function handle(EntityManager $em)
    {
        try {
            $connection = $em->getConnection();
            $connection->connect();
            $this->info('Doctrine connection successful!');
            
            // Test entity metadata
            $metadata = $em->getMetadataFactory()->getAllMetadata();
            $this->info('Found ' . count($metadata) . ' mapped entities');
            
            foreach ($metadata as $classMetadata) {
                $this->info('Found entity: ' . $classMetadata->getName());
            }
        } catch (\Exception $e) {
            $this->error('Doctrine connection failed: ' . $e->getMessage());
        }
    }
} 