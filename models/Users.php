<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $fio
 * @property string $accessToken
 * @property string $authKey
 * @property integer $role
 *
 * @property DataUsers[] $dataUsers
 */
class Users extends \yii\db\ActiveRecord
{
    private $_salt = 'kuku';
	private $_user = false;
	private $_hashPass = '';
	
	const SCENARIO_LOGIN = 'login';
	const SCENARIO_REGIST = 'regist';
	
	public function scenarios()
    {
		$scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] =  ['username','password'];
        $scenarios[self::SCENARIO_REGIST] =  ['username','password', 'fio', 'role'];
    
        return $scenarios;
		
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password','fio'], 'required','message' => 'Пожалуйста заполните поле'],
            ['username', 'string', 'max' => 20,'message' => 'Логин не должен превышать 20 символов'],
            ['fio', 'string', 'max' => 255],
            ['username', 'unique','on' => self::SCENARIO_REGIST, 'message' => 'Логин уже используется'],
            ['role', 'boolean','on' => self::SCENARIO_REGIST],
            ['password', 'validatePassword','on' => self::SCENARIO_LOGIN ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'fio' => 'ФИО',
            'role' => 'Админ',
        ];
    }

	 
	public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
			
            $check = static::findOne([
								'password' => $this->getHashPass()
								]);
               
            if(!$check) 
                $this->addError($attribute, 'Не верно введен логин или пароль.');
			else
			{
				
				$check->accessToken = Yii::$app->security->generateRandomString();
				$check->save();
			}
			
        }
    }
	
	
	public function login()
    {
        if($this->validate()) {
			
			 return Yii::$app->user->login($this->getUser(),3600*24);
        }
		
		return false;
    }
	
	
	
    public function saveUser()
    {
   
		if($this->validate()) {
			
			 $this->password = $this->getHashPass();
			
			 $this->authKey = Yii::$app->security->generateRandomString();
		
			 return $this->save(false);
			 
        }
			
		return false;	
        
    }
	
	 /**
     * Finds user by [[username]]
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

	

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataUsers()
    {
        return $this->hasMany(DataUsers::className(), ['id'=> 'user_id']);
    }
	
	public function getHashPass()
	{
		if(empty($this->_hashPass))
				$this->_hashPass = md5($this->_salt.$this->username.$this->password);
	
		return  $this->_hashPass;
	}
	
	
	
}
