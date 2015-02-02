<?php

namespace app\models;

class User extends \yii\base\Object implements \yii\web\IdentityInterface
{
    public $_id;
    public $login;
    public $password;
    public $name;
    public $role;
    public $username;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        if ($user = Users::find()->select(['_id', 'name', 'login', 'password', 'role'])->where(['_id' => (object)$id])->one()) {
            //var_dump($user);die();
            return new static($user);
        }
            return null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
//        foreach (self::$users as $user) {
//            if ($user['accessToken'] === $token) {
//                return new static($user);
//            }
//        }

//        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        if ($user = Users::find()->select(['_id', 'name', 'login', 'password', 'role'])->where(['login' => $username])->one()) {
//        foreach (self::$users as $user) {
//            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
//            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->_id;
    }
    
    public function __get($name)
    {
            return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
//        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
//        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        //return $this->password === $password;
        if ($user = Users::find()->where(['password' => md5($password)])->one()) {
            return true;
        }
        return false;
    }
}
