<?php

namespace app\models;

use Yii;

use common\models\User;
use app\models\Army;
use app\models\Unit;

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
    
    public function getArmyOptions()
    {
        $armyOptions = array();
        $allArmies = Army::find()->all();
        foreach($allArmies as $army)
        {
            $username = User::findOne($army->user)->username;
            $armyOptions[$army->id] = $username;
        }
        return $armyOptions;
    }
    
    public function getUnitOptions()
    {
        $unitOptions = array();
        $allUnits = Unit::find()->all();
        foreach($allUnits as $unit)
        {
            $unitOptions[$unit->id] = $unit->name;
        }
        return $unitOptions;
    }
}
