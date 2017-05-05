<?php
namespace Controllers;

use Phalcon\Http\Response;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Regex as RegexValidator;

class FileController extends Controller
{
    /** @var  Validation */
    protected $validator;
    /** @var array */
    protected $errorMessages = [];

    public function onConstruct()
    {
        $this->validator = new Validation();
        $this->validator->add('filename', new RegexValidator([
            'pattern'=> '/^[A-z0-9]{3,200}$/',
            'message'=> 'Filename has to contains only letters and digits, min size 3, max - 200!'
        ]));
    }


    protected function checkFilename(string $filename)
    {
        $messages = $this->validator->validate(['filename'=> $filename]);
        if (count($messages) > 0) {
            $message = $messages->current();
            throw new \Exception($message->getMessage(), 401);
        }
    }

    public function getFile($filename)
    {
        $this->checkFilename($filename);
        return json_encode(['Filename is good']);
    }

    public function createFile($filename)
    {
        if (!$this->checkFilename($filename)) {
            return;
        }
    }

    public function updateFile($filename)
    {
        if (!$this->checkFilename($filename)) {
            return;
        }
    }

    public function getFileMeta($filename)
    {
        if (!$this->checkFilename($filename)) {
            return;
        }
    }

    public function getList()
    {

    }

}

