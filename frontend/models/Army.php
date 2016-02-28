<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "army".
 *
 * @property integer $id
 * @property integer $user
 * @property integer $kingdom
 */
class Army extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'army';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user', 'kingdom'], 'required'],
            [['user', 'kingdom'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'kingdom' => 'Kingdom',
        ];
    }
}
