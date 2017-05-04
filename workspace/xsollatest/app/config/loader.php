<?php

/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();


$loader->registerNamespaces([
    'Controllers' => $config->application->controllersDir,
    'Models' => $config->application->modelsDir,
])->register();
