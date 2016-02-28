<?php

namespace app\models;

use Yii;
use app\models\Army;

/**
 * This is the model class for table "kingdom".
 *
 * @property integer $id
 * @property integer $user
 * @property string $points
 * @property string $economy
 * @property string $loyalty
 * @property string $stability
 * @property string $unrest
 */
class Kingdom extends \yii\db\ActiveRecord
{
    const TIME_BETWEEN_UPDATES = 360; //1 Day
    const POINTS_TO_GOLD_SCALAR = 1000;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kingdom';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user'], 'required'],
            [['user'], 'integer'],
            [['points', 'economy', 'loyalty', 'stability', 'unrest'], 'number'],
            [['user'], 'unique']
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
            'points' => 'Points',
            'economy' => 'Economy',
            'loyalty' => 'Loyalty',
            'stability' => 'Stability',
            'unrest' => 'Unrest',
        ];
    }
    
    public function updateKingdom()
    {
        //Determine what portion of a full update we are performing.
        $date = new \DateTime();
        $currentTimestamp = $date->getTimestamp();
        $timeSinceLastUpdate = $currentTimestamp - $this->updated_at;
        $this->updated_at = $currentTimestamp;
        $this->save();
        $scalar = $timeSinceLastUpdate / Kingdom::TIME_BETWEEN_UPDATES;
        //Perform our update.
        $this->performUpkeep($scalar);
        $this->performIncome($scalar);
    }
    
    public function performIncome($scalar)
    {
        $taxes = (rand(1, 20) + $this->economy) / 3;
        $this->points += max($taxes * $scalar, 0);
        $this->save();
    }
    
    public function performUpkeep($scalar)
    {
        $this->performStabilityCheck($scalar);
        $this->performConsumption($scalar);
        $this->performUnrest($scalar);
        $this->save();
    }
    
    public function performUnrest($scalar)
    {
        $unrestModifier = 0;
        if($this->loyalty < 0)
            $unrestModifier++;
        if($this->stability < 0)
            $unrestModifier++;
        if($this->economy < 0)
            $unrestModifier++;
        $this->unrest += $unrestModifier * $scalar;
        $this->save();
    }
    
    public function performConsumption($scalar)
    {
        //Pay our army's consumption.
        $army = Army::find()->where(['kingdom' => $this->id])->one();
        $this->points -= max($army->getStats()['consumption'] * $scalar, 0);
        //Check if we went negative.
        if($this->points < 0)
        {
            $this->points = 0;
            $this->unrest += 2 * $scalar;
        }
        $this->save();
    }
    
    public function performStabilityCheck($scalar)
    {
        $stabilityCheck = rand(1, 20) + $this->stability - $this->unrest;
        $difficulty = $this->getControlDifficulty();
        if($stabilityCheck >= $difficulty)
        {   
            $this->unrest -= 1 * $scalar;
            if($this->unrest < 0)
            {
                $this->unrest = 0;
                $this->points += max(1 * $scalar,0);
            }
        }
        else if($stabilityCheck >= ($difficulty - 5))
            $this->unrest += 1 * $scalar;
        else 
            $this->unrest += rand(1,4) * $scalar;
        $this->save();
    }
    
    public function getControlDifficulty()
    {
        return 20 + $this->getSize();
    }
    
    public function getSize()
    {
//TODO - Calculate Size--------------------------------------------------------------------------
        return 0;
    }
    
    //Deplete stats according to battle results.
    public function performBattleLoss($losses)
    {
        $this->updateKingdom();
        $lossLevel = pow(2, log($losses, 2) - 5);
        $this->loyalty -= max($lossLevel, 0);
        $this->economy -= max($lossLevel+1, 0);
        $this->stability -= max($lossLevel, 0);
        $this->save();
    }
}
