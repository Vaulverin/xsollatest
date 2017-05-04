<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */
use Controllers\FileController;
use Models\Users;
use Phalcon\Http\Response;

/**
 * Add your routes here
 */
$app->get('/', function () use($app) {
    echo $this['view']->render('index');
});

// Create new user
$app->post('/create-user', function() use ($app) {
    $user = new Users();
    $response = new Response();
    if ($user->create($_POST, array('name', 'password')) === false) {
        $response->setStatusCode(409, "Conflict");
        $messages = [];
        foreach ($user->getMessages() as $message) {
            $messages[] = $message->getMessage();
        }
        $response->setJsonContent(
            [
                "status"   => "ERROR",
                "messages" => $messages,
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
    return $response;
});


$fileController = new FileController();
// Get file list for current user
$app->get('/list', [$fileController, 'getList']);

// Get file with <filename>
$app->get('/file/{file}', [$fileController, 'getFile']);
// Create file with <filename>
$app->post('/file/{file}', [$fileController, 'createFile']);
// Update file with <filename>
$app->put('/file/{file}', [$fileController, 'updateFile']);
// Get file metadata with <filename>
$app->get('/file/{file}/meta', [$fileController, 'getFileMeta']);

/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app['view']->render('404');
});