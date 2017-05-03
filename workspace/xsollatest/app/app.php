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

// Create new user
$app->post('/add-user', function() use ($app) {
    $user = $app->request->getJsonRawBody();

});

// Get file list for current user
$app->get('/list', function() use ($app) {

});

// File name can contains only letters and digits
$filename = '{file:[A-z0-9]+.[A-z]{1,5}';

// Get file with <filename>
$app->get('/'.$filename, function(File $file) use ($app) {

});
// Create file with <filename>
$app->post('/'.$filename, function(File $file) use ($app) {

});
// Update file with <filename>
$app->update('/'.$filename, function(File $file) use ($app) {

});
// Get file metadata with <filename>
$app->get('/'.$filename.'/meta', function(File $file) use ($app) {

});

/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});
