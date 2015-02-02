<?php

namespace app\models;

use Yii;

/**
 * This is the model class for collection "users".
 *
 * @property \MongoId|string $_id
 * @property mixed $name
 * @property mixed $login
 * @property mixed $password
 * @property mixed $role
 */
class Users extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return ['yp', 'users'];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'name',
            'login',
            'password',
            'role',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'login', 'password', 'role'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
            'name' => 'Name',
            'login' => 'Login',
            'password' => 'Password',
            'role' => 'Role',
        ];
    }
    
    public function beforeSave($insert) {
        $this->password = md5($this->password);
        parent::beforeSave($insert);
        return true;
    }
}
