<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "unit".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $level
 * @property integer $hitDice
 * @property integer $isRanged
 */
class Unit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'level', 'hitDice'], 'required'],
            [['description'], 'string'],
            [['level', 'hitDice', 'isRanged'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'level' => 'Level',
            'hitDice' => 'Hit Dice',
            'isRanged' => 'Is Ranged',
        ];
    }
    
    public function getAttackOptions()
    {
        return [0 => 'Melee', 1 => 'Ranged'];
    }
    
    //Get cost in BP
    public function getCost()
    {
        return $this->level + pow(2,log(1, 2) - (6 - $this->level / 2));
    }
}
