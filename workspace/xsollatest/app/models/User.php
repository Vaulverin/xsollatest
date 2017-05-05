<?php

namespace Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\Regex as RegexValidator;

class User extends Model
{
    const USERS_FOLDER = '../users/';
    public static $currentUser = User::class;

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $name;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $password;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=true)
     */
    public $token;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("xsollatest");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return User[]|User
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return User
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add('name', new Uniqueness(
            [
                "model"   => $this,
                "message" => "User name has to be unique",
            ]
        ));
        $validator->add(
            ['name', 'password'],
            new RegexValidator(
                [
                    "pattern" => [
                        "name" => "/^[A-z0-9]{3,255}$/",
                        "password" => "/^[A-z0-9]{3,255}$/",
                    ],
                    "message" => [
                        "name" => "User name must contains letters and digits only, min length - 3, max length - 255",
                        "password" => "User password must contains letters and digits only, min length - 3, max length - 255",
                    ]
                ]
            ));
        $this->validate($validator);
        if ($this->validationHasFailed() === true) {
            return false;
        }
        return true;
    }

    public function beforeCreate()
    {
        $this->token = sha1($this->name.$this->password.random_bytes(40));
        $this->password = $this->encryptPass($this->password);
    }

    protected function encryptPass($pass)
    {
        return sha1($pass.'octopus');
    }

    public function afterCreate()
    {
        mkdir($this::USERS_FOLDER. $this->name."/", 0777);
    }

    public static function directory()
    {
        if (static::$currentUser instanceof User && isset(static::$currentUser->name)) {
            return static::USERS_FOLDER.static::$currentUser->name.'/';
        }
        throw new \Exception('User not found', 404);
    }
}
