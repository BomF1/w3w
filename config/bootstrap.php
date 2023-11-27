<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = [__DIR__ . '/../src/Entity'];
$isDevMode = true;

$dbParameters = [
    'driver'   => 'pdo_mysql',
    'user'     => 'root',
    'password' => '',
    'dbname'   => 'w3w',
    'host'     => 'localhost',
    'port'     => 3306
];

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParameters, $config);

return $entityManager;
//mysql://root:@localhost:3306/gymBeam?serverVersion=8.0
