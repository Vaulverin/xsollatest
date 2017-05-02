<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */

/**
 * Add your routes here
 */
$app->get('/', function () {
    echo $this['view']->render('index');
});

$app->post('/add-user', function() use ($app) {
    $user = $app->request->getJsonRawBody();

});

$app->get('/list', function() use ($app) {

});

$filename = '{file:[A-z0-9]+.[A-z]{1,5}';
$app->get('/list', function() use ($app) {

});

$app->get('/'.$filename, function(File $file) use ($app) {

});

$app->post('/'.$filename, function(File $file) use ($app) {

});

$app->update('/'.$filename, function(File $file) use ($app) {

});

$app->get('/'.$filename.'/meta', function(File $file) use ($app) {

});

/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});
