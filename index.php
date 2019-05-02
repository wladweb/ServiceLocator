<?php

ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

echo "<h3>Service Locator</h3>";

require_once 'vendor/autoload.php';

try {
    $container = Wladweb\ServiceLocator\Container::getContainer();
} catch (Wladweb\ServiceLocator\Exceptions\ContainerException $e) {
    echo $e->getCode();
    echo '<br>';
    echo $e->getMessage();
}

var_dump($container);


