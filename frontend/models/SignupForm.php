<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

use app\models\Kingdom;
use app\models\Army;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->role = 10;
        
        if($user->save())
        {
            //Create a kingdom and army for this user.
            $kingdom = new Kingdom();
            $kingdom->user = $user->id;
            $date = new \DateTime();
            $kingdom->created_at = $date->getTimestamp();
            $kingdom->updated_at = $kingdom->created_at;
            $kingdom->save();
            $army = new Army();
            $army->user = $user->id;
            $army->kingdom = $kingdom->id;
            $army->save();
            return $user;
        }
        else
            return null;
    }
}
