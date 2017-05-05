<?php

/**
 * Registering an autoloader
 */
$loader = new \Phalcon\Loader();


$loader->registerNamespaces([
    'Models' => $config->application->modelsDir,
])->register();
