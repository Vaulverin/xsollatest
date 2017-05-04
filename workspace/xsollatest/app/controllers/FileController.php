<?php
namespace Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Regex as RegexValidator;

class FileController extends Controller
{
    /** @var  Validation */
    protected $validator;
    /** @var array */
    protected $errorMessages = [];

    public function initialize()
    {
        $this->validator = new Validation();
        $this->validator->add('filename', new RegexValidator([
            'pattern'=> '/^[A-z0-9]{3,200}\.[A-z]{1,5}$/',
            'message'=> 'Filename has to contains only letters and digits, min size 3, max - 200! Extension has to contains only letters, min size 1, max - 5!'
        ]));
    }


    protected function isFilenameGood(string $filename)
    {
        $messages = $this->validator->validate(['filename'=> $filename]);
        if (count($messages) > 0) {
            $this->errorMessages = array_merge($this->errorMessages, $messages);
            return false;
        }
        return true;
    }

    public function getFile($filename)
    {
        if (!$this->isFilenameGood($filename)) {
            return;
        }
        return json_encode(['Filename is good']);
    }

    public function createFile($filename)
    {
        if (!$this->isFilenameGood($filename)) {
            return;
        }
    }

    public function updateFile($filename)
    {
        if (!$this->isFilenameGood($filename)) {
            return;
        }
    }

    public function getFileMeta($filename)
    {
        if (!$this->isFilenameGood($filename)) {
            return;
        }
    }

    public function getList()
    {

    }

    public function beforeExecuteRoute($dispatcher)
    {
        // Выполняется до запуска любого найденного действия
        if ($dispatcher->getActionName() === "save") {
            $this->flash->error(
                "У вас недостаточно прав для сохранения записей"
            );

            $this->dispatcher->forward(
                [
                    "controller" => "home",
                    "action"     => "index",
                ]
            );

            return false;
        }
    }

    public function afterExecuteRoute($dispatcher)
    {
        // Выполняется после каждого выполненного действия
    }

}

