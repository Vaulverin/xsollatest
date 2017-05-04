<?php
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'    => 'Mysql',
        'host'       => 'localhost',
        'username'   => 'homestead',
        'password'   => 'secret',
        'dbname'     => 'xsollatest',
        'charset'    => 'utf8',
    ],

    'application' => [
        'modelsDir'      => APP_PATH . '/models/',
        'controllersDir' => APP_PATH . '/controllers/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'baseUri'        => '/xsollatest/',
    ]
]);
