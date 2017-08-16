<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "data_users".
 *
 * @property integer $id
 * @property string $req
 * @property string $res
 * @property integer $user_id
 *
 * @property Users $user
 */
class DataUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['req'], 'string'],
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['res'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'req' => 'Запрос',
            'res' => 'Ответ',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['user_id'=> 'id' ]);
    }
}
