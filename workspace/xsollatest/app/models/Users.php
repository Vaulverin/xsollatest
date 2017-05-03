<?php

namespace Models;

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Uniqueness;

class Users extends Model
{

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
     * @return Users[]|Users
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function validation()
    {
        $this->validate(
            new Uniqueness(
                [
                    "field"   => "name",
                    "message" => "User name has to be unique",
                ]
            )
        );
        $validator = new Validation();
        $validator->add(
            ['name', 'password'],
            new RegexValidator(
                [
                    "pattern" => [
                        "name" => "/^[A-z0-9]{3,255}$/",
                        "password" => "/^[a-z]{3,255}$/",
                    ],
                    "message" => [
                        "name" => "User name must contains letters and digits only, min length - 3, max length - 255",
                        "password" => "User password must contains letters and digits only, min length - 3, max length - 255",
                    ]
                ]
            ));
        $this->validate($validator);
        // Проверяем, были ли получены какие-либо сообщения при валидации
        if ($this->validationHasFailed() === true) {
            return false;
        }
        return true;
    }

    public function beforeCreate() {
        $this->token = sha1($this->name.$this->password.random_bytes(40));
    }

}
