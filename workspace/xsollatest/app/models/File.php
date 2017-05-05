<?php

namespace Models;


class File
{
    /** @var  string */
    protected $filename;

    protected static $permittedExt = [
        'json','text','jpeg','jpg','png','pdf'
    ];

    public function __construct($filename)
    {
        static::checkName($filename);
        $this->filename = $filename;
    }

    public static function checkName($filename)
    {
        if (!preg_match('/^[A-z0-9]{3,200}\..{1,200}$/', $filename)) {
            throw new \Exception('Filename has to contains only letters and digits, min size 3, max - 200!', 400);
        }
        $ext = substr($filename, strpos($filename, '.') + 1);
        if (!in_array($ext, static::$permittedExt))
        {
            throw new \Exception('Permitted only this extensions: '.implode(', ', static::$permittedExt), 400);
        }
    }

    public function exists()
    {
        return file_exists($this->filePath());
    }

    public function filePath()
    {
        return User::directory().$this->filename;
    }

    public function metaData()
    {
        $f = fopen($this->filePath());
        $meta = stream_get_meta_data($f);
        fclose($f);
        return $meta;
    }

    public function createFromResponse($content)
    {
        if ($this->exists()) {
            throw new \Exception('File already exists!', 400);
        }
        file_put_contents($this->filePath(), base64_decode($content));
        return true;
    }

}