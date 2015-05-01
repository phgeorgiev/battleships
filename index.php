<?php

require_once __DIR__ . '/vendor/autoload.php';

use Framework\Web\Application as WebApplication;
use Framework\Console\Application as ConsoleApplication;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

$container = new ContainerBuilder();
$loader = new XmlFileLoader($container, new FileLocator(__DIR__));
$loader->load(__DIR__ . '/src/config/services.xml');

$container->setParameter('document_root', __DIR__);
$container->compile();

if (php_sapi_name() == 'cli') {
    $app = new ConsoleApplication($container);
}
else {
    $app = new WebApplication($container);
}

$app->run();
