<?php

namespace app\models;

use Yii;
use yii\db\Expression;

use common\models\User;
use app\models\UnitConnections;
use app\models\Unit;

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
    
    public function getUserOptions()
    {
        $userOptions = array();
        $allUsers = User::find()->all();
        foreach($allUsers as $user)
        {
            $userOptions[$user->id] = $user->username;
        }
        return $userOptions;
    }
    
    //Calculates stats and returns them as an array. 
    //Returns 'count', 'health', 'defense', 'offense', 'offense_ranged', and 'level'.
    public function getStatsById($armyId)
    {
        $LEVEL_MODIFIER_EXPONENT = 6;
        //Get the sums necessary to calculate stats.
        $allUnits = UnitConnections::find()->where(['army' => $armyId])->all();
        $unitCount = 0;
        $unitCountRanged = 0;
        $sumUnitHealth = 0;
        $sumUnitLevel = 0;
        $sumUnitLevelRanged = 0;
        foreach($allUnits as $unitConnection)
        {
            $unit = Unit::findOne($unitConnection->unit);
            $unitCount += $unitConnection->count;
            $sumUnitHealth += $unit->hitDice * $unitConnection->count;
            $sumUnitLevel += $unit->level * $unitConnection->count;
            if($unit->isRanged)
            {
                $sumUnitLevelRanged += $unit->level * $unitConnection->count;
                $unitCountRanged += $unitConnection->count;
            }
        }
        $stats = array();
        $stats['count'] = $unitCount;
        $stats['health_sum'] = $sumUnitHealth;
        if($unitCount > 0)
        {
            $stats['level'] = floor($sumUnitLevel / $unitCount) + floor(pow(2, floor(log($unitCount, 2)) - $LEVEL_MODIFIER_EXPONENT));
            $stats['health'] = floor($stats['level'] * (($sumUnitHealth / $unitCount) / 2));
            $stats['defense'] = $stats['level'] + 10;
            $stats['offense'] = $stats['level'];
        }
        else
        {
            $stats['level'] = 0;
            $stats['health'] = 0;
            $stats['defense'] = 0;
            $stats['offense'] = 0;
        }
        if($unitCountRanged > 0)
            $stats['offense_ranged'] = floor($sumUnitLevelRanged / $unitCountRanged) + pow(2, floor(log($unitCountRanged, 2)) - 6);
        else
            $stats['offense_ranged'] = 0;
        return $stats;
    }
    
    public function getStats()
    {
        return Army::getStatsById($this->id);
    }
    
    //Simulates an attack from the given army and returns the amount of damage dealt.
    //  Damage is handled seperately so that two armies can attack simultaneously before damage is applied.
    public function calculateDamageFromAttack($attackingArmy, $rangedOnly=false)
    {
        $attackerStats = $attackingArmy->getStats();
        $defenderStats = $this->getStats();
        $offensiveCheck = rand(1,20);
        $critical = $offensiveCheck >= 19; //If critical, always deal at least 1 damage.
        if($rangedOnly)
            $offensiveCheck += $attackerStats['offense_ranged'];
        else
            $offensiveCheck += $attackerStats['offense'];
        $damageDealt = $offensiveCheck - $defenderStats['defense'];
        if($critical)
            return max($damageDealt, 1);
        else
            return max($damageDealt, 0);
    }
    
    //Destroys units based on damage recieved. Returns an array of units desroyed.
    //  Returns: [UNIT_ID => LOSS_COUNT, ...]
    public function resolveDamage($damage)
    {
        $lossRecord = array();
        //Calculate damage in terms of unit hitdice.
        $stats = $this->getStats();
        $healthLoss = ceil(($damage / $stats['health']) * $stats['health_sum']);
        //Always deal at least one damage if damage is greater than 0.
        if(($damage > 0) && ($healthLoss < 1))
            $healthLoss = 1;
        //Remove healthLoss's worth of hit dice from the army.
        $allUnits = UnitConnections::find()->where(['army' => $this->id])->orderBy(new Expression('rand()'))->all();
        foreach($allUnits as $unitConnection)
        {
            $unit = Unit::findOne($unitConnection->unit);
            if($unit->hitDice <= $healthLoss)
            {
                $i = 0;
                $unitLossCount = 0;
                //Destroy as many of these units as we can. Track how many were lost.
                while(($healthLoss >= $unit->hitDice) && ($unitConnection->count > 0))
                {
                    $healthLoss -= $unit->hitDice;
                    $unitLossCount++;
                    $unitConnection->count--;
                    $unitConnection->save();
                }
                //Record our losses.
                if($unitLossCount > 0)
                {
                    $lossRecord[$unit->id] = $unitLossCount;
                    //Check if this unit type has been completely wiped out. If so, delete this connection.
                    if($unitConnection->count == 0)
                    {
                        $unitConnection->delete();
                    }
                }
            }
            if($healthLoss <= 0)
                break;
        }
        return $lossRecord;
    }
}
