<?php
namespace app\models;

use yii\base\Model;
use app\models\Managers;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $Tpassword;
    public $confirm_password;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\Managers', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 8],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\Managers', 'message' => 'This email address has already been taken.'],

            [['Tpassword','confirm_password'], 'required'],
            [['Tpassword','confirm_password'], 'string', 'min' => 6],

            ['confirm_password','compare','compareAttribute' => 'Tpassword']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'Tpassword' => 'Password'
        ];
    }

    /**
     * Signs user up.
     *
     * @return Managers|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new Managers();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->password = "deshario"; // It'll be not valid because its temp variable of user model
        $user->status = \app\models\Managers::STATUS_WAITING;
        $user->setPassword($this->Tpassword);
        $user->generateAuthKey();
        $user->created_at = time();
        $user->updated_at = time();
        $user->manager_key = $user->getRandomKey(10);

        return $user->save() ? $user : null;
    }
}