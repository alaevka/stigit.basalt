<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\FailedEntries;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            [['username'], 'string', 'length' => [1,24]],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            
        ];
    }

    /**
     * Валидация пароля
     * 
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            
            if (!$user || !$user->validatePassword($this->password)) {

                //запись не успешной авторизации в таблицу FAILED_ENTRIES 
                $failedEntries = new FailedEntries;
                $failedEntries->LOGIN = $this->username;
                $failedEntries->PASSWORD = $this->password;
                $failedEntries->TRACT_DATETIME = new \yii\db\Expression('SYSDATE');
                $failedEntries->USER_IP = $_SERVER['REMOTE_ADDR'];
                $failedEntries->save(false);

                $this->addError($attribute, Yii::$app->params['auth_fail']);
            }
        }
    }

    /**
     * Авторизация пользователя
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Поиск пользователя по введенному логину
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
