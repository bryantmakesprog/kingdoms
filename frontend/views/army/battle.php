<?php

/*
 * Displays the results of a battle. Takes in:
 * userLosses: Any units lost by the user.
 * userWipe: Did the user lose all units during this battle?
 * targetLosses: Any units lost by the target.
 * targetWipe: Did the target lose all units during this battle?
 * raid: Did this battle turn into a raid? TODO
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Army;
use common\models\User;
use app\models\Unit;

/* @var $this yii\web\View */
/* @var $model app\models\Army */

$this->title = "Battle Results";
//$this->params['breadcrumbs'][] = ['label' => 'Armies', 'url' => ['battle']];
$this->params['breadcrumbs'][] = $this->title;

$targetArmy = Army::find()->where(['user' => $target])->one();
$targetUser = User::findOne($targetArmy->user)->username;
?>
<div class="army-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        A battle occurred between you and <?= $targetUser ?>!
    </p>

    <!--Draw user's losses if not a wipe.-------------------------------------->
    <?php if(count($userLosses)) { ?>
        <p>You lost:</p>
        <ul>
            <?php
                foreach($userLosses as $unitId => $unitLosses)
                {
                    $unit = Unit::findOne($unitId);
                    echo "<li>" . $unit->name . " - " . $unitLosses . "</li>";
                }
            ?>
        </ul>
    <?php 
        } //End if(count($userLosses)) 
        else if(!($raid && !count($targetLosses))) {
    ?>
        <p>You survived unscathed!</p>
    <?php } //End else if(!$raid) ?>
    <!--End Draw user losses.-------------------------------------------------->
    
    <!--Draw target's losses if not a raid.------------------------------------>
        <?php if(count($targetLosses)) { ?>
            <p><?= $targetUser ?> lost:</p>
            <ul>
                <?php
                    foreach($targetLosses as $unitId => $unitLosses)
                    {
                        $unit = Unit::findOne($unitId);
                        echo "<li>" . $unit->name . " - " . $unitLosses . "</li>";
                    }
                ?>
            </ul>
        <?php 
            } //End if(count($targetLosses)) 
            else if(!$raid) {
        ?>
            <p><?= $targetUser ?> survived unscathed!</p>
        <?php } //End else if(!$raid) ?>
    <!--End Draw target losses.------------------------------------------------>
        
    <!--Draw if user wiped.---------------------------------------------------->
        <?php if($userWipe) { ?>
            <p>You were routed.</p>
        <?php } //End if($userWipe) ?>
    <!--End User Wipe.--------------------------------------------------------->
    
    <!--Draw if target wiped.-------------------------------------------------->
        <?php if($targetWipe) { ?>
            <p><?= $targetUser ?> was routed.</p>
        <?php } //End if($userWipe) ?>
    <!--End target Wipe.------------------------------------------------------->
    
    <!--Draw raid status------------------------------------------------------->
        <?php if($raid) { ?>
            <p>With no defenders remaining, your attack on <?= $targetUser ?> became a raid!</p>
        <?php } //End if($raid) ?>
    <!--End draw raid---------------------------------------------------------->
    
    <p>
        <?= Html::a('Attack ' . $targetUser, ['attack', 'target' => $target], ['class' => 'btn btn-primary']) ?>
    </p>
</div>
