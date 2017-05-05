<?php
/**
 * Local variables
 * @var \Phalcon\Mvc\Micro $app
 */
use Models\File;
use Models\User;
use Phalcon\Http\Response;

$app->get('/', function () use($app) {
    echo 'WELCOME TO FILES API';
});

/**
 * Create user.
 */
$app->post('/create-user', function() use ($app) {
    $user = new User();
    if ($user->create($_POST, array('name', 'password')) === false) {
        // if validation failed
        $messages = [];
        foreach ($user->getMessages() as $message) {
            $messages[] = $message->getMessage();
        }
        throw new Exception(json_encode($messages), 409);
    }
    return [
        "status" => "USER SUCCESSFULLY CREATED",
        "data"   => ['token'=> $user->token],
    ];
});

#region Working with files
/**
 * Get file list for current user
 */
$app->get('/list', function() {
    $items = scandir(User::directory());
    $files = [];
    foreach ($items as $item) {
        if (is_file($item)) {
            $files[] = $item;
        }
    }
    if (count($files) > 0) {
        return $files;
    }
    return ['No files in directory'];
});

/**
 * Get file with <filename>
 */
$app->get('/file/{file}', function($filename) use ($app) { });

/**
 * Create file with <filename>
 */
$app->post('/file/{file}', function($filename) use ($app) {
    if (File::exists($filename)) {
        throw new Exception('File already exists!', 400);
    }
    var_dump($app->request->getRawBody());
    var_dump($app->request->getContentType());
});

/**
 * Update file with <filename>
 */
$app->put('/file/{file}', function($filename) use ($app) {
    if (!File::exists($filename)) {
        throw new Exception('File doesn\'t exist!', 400);
    }
});

/**
 * Get file metadata with <filename>
 */
$app->get('/file/{file}/meta', function($filename) use ($app) {
    $file = new File($filename);
    return $file->metaData();
});
#endregion

#region System calls
/**
 * Check auth if needed.
 */
$app->before(function() use ($app) {
    $request = $app->request;
    if ($request->get('_url') == '/create-user') {
        return true;
    }
    // Checking user token
    $token = $request->getHeader('Authorization');
    if (!preg_match('/^[A-z0-9]{40}$/', $token)) {
        throw new Exception('Unauthorized', 401);
    }
    // Is user exist?
    User::$currentUser = User::findFirst(['token = "'.$token.'"']);
    if (User::$currentUser === false) {
        throw new Exception('User not found', 404);
    }
});

/**
 * Send response with error.
 */
$app->error(function(Exception $exception) use ($app) {
    $response = new Response();
    $response->setStatusCode($exception->getCode());
    $response->setJsonContent(
        [
            "status"   => "failed",
            "messages" => $exception->getMessage(),
        ]
    );
    return $response;
});

/**
 * Not found handler
 */
$app->notFound(function () use($app) {
    throw new Exception("Not Found", 404);
});

/**
 * Send final response.
 */
$app->after(function () use ($app) {
        $response = new Response();
        $response->setStatusCode(200);
        $response->setJsonContent($app->getReturnedValue());
        $response->send();
    });
#endregion