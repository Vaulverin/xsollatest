<?php

namespace Models;


class File
{
    /** @var  string */
    protected $filename;

    public function __construct($filename)
    {
        static::checkName($filename);
        $this->filename = $filename;
    }

    public static function checkName($filename)
    {
        if (!preg_match('/^[A-z0-9]{3,200}$/', $filename)) {
            throw new \Exception('Filename has to contains only letters and digits, min size 3, max - 200!');
        }
    }

    public static function exists($filename)
    {
        return file_exists(User::directory().$filename);
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

}