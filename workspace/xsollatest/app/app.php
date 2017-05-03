<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */
use Models\Users;
use Phalcon\Http\Response;

/**
 * Add your routes here
 */
$app->get('/', function () {
    echo $this['view']->render('index');
});

// Create new user
$app->post('/create-user', function() use ($app) {
    $user = new Users();
    $response = new Response();
    if ($user->create($_POST, array('name', 'password')) === false) {
        $response->setStatusCode(409, "Conflict");
        $response->setJsonContent(
            [
                "status"   => "ERROR",
                "messages" => $user->getMessages(),
            ]
        );
    } else {
        $response->setStatusCode(201, "Created");
        $response->setJsonContent(
            [
                "status" => "USER SUCCESSFULLY CREATED",
                "data"   => ['token'=> $user->token],
            ]
        );
    }
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
