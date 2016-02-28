<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "unitconnections".
 *
 * @property integer $id
 * @property integer $army
 * @property integer $unit
 * @property integer $count
 */
class UnitConnections extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unitconnections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['army', 'unit', 'count'], 'required'],
            [['army', 'unit', 'count'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'army' => 'Army',
            'unit' => 'Unit',
            'count' => 'Count',
        ];
    }
}
