<?php

namespace Models;


use Phalcon\Exception;
use Phalcon\Http\Request;

class File
{
    /** @var  string */
    protected $filename;

    protected static $permittedExt = [
        'json','txt','jpeg','jpg','png','pdf'
    ];

    public function __construct($filename)
    {
        static::checkName($filename);
        $this->filename = $filename;
    }

    public static function checkName($filename)
    {
        if (!preg_match('/^[A-z0-9]{1,200}/', $filename)) {
            throw new Exception('Filename has to contains only letters and digits, max length - 200 symbols!', 400);
        }
        $ext = substr($filename, strpos($filename, '.') + 1);
        if (!in_array($ext, static::$permittedExt))
        {
            throw new Exception('Permitted only this extensions: '.implode(', ', static::$permittedExt), 400);
        }
    }

    public function exists()
    {
        return file_exists($this->path());
    }

    public function path()
    {
        return User::directory().$this->filename;
    }

    public function mimeType()
    {
        return mime_content_type($this->path());
    }

    public function extension()
    {
        return pathinfo($this->path())['extension'];
    }

    public function content()
    {
        return file_get_contents($this->path());
    }
    public function name()
    {
        return $this->filename;
    }

    public function metaData()
    {
        if (!$this->exists()) {
            throw new Exception('File not exists!', 400);
        }
        $meta = [
            'name'=> $this->filename,
            'dateTime'=> fileatime($this->path()),
            'fileSize'=> filesize($this->path()),
            'mimeType'=> $this->mimeType()
        ];
        if ($this->extension() != 'txt' && exif_imagetype($this->path()) !== false) {
            $imageInfo = [];
            $info = getimagesize($this->path(), $imageInfo);
            $meta['imageInfo'] = [
                'info'=> $imageInfo,
                'width'=> $info[0],
                'height'=> $info[1],
                'bits'=> $info['bits'],
                'channels'=> $info['channels']
            ];
        }
        return $meta;
    }

    public function createFromRequest(Request $request)
    {
        if ($this->exists()) {
            throw new Exception('File already exists!', 400);
        }
        file_put_contents($this->path(), $request->getRawBody());
        return true;
    }

    public function updateFromRequest(Request $request)
    {
        if (!$this->exists()) {
            throw new Exception('File not exists!', 400);
        }
        file_put_contents($this->path(), $request->getRawBody());
        return true;
    }

}