<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

use app\models\Army;
use app\models\Kingdom;
use app\models\UnitConnections;
use app\models\Unit;

/* @var $this yii\web\View */
/* @var $model app\models\Army */

$army = Army::find()->where(['user' => Yii::$app->user->identity->id])->one();
$allUnits = UnitConnections::find()->where(['army' => $army->id])->all();

if($messageType == 'success')
{
    echo '<div class="alert alert-success">';
        echo $message;
    echo "</div>";
}
if($messageType == 'failure')
{
    echo '<div class="alert alert-danger">';
        echo $message;
    echo "</div>";
}

$this->title = "Army - Roster";
$this->params['breadcrumbs'][] = ['label' => 'Kingdom', 'url' => ['/kingdom/status']];
$this->params['breadcrumbs'][] = ['label' => 'Army', 'url' => ['/army/status']];
$this->params['breadcrumbs'][] = "Roster";
?>
<div class="army-view">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a('Recruit', ['/army/recruit',], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php if(count($allUnits)) { ?>
        <table class='table table-hover'>
            <thead>
                <tr>
                    <td>Unit</td>
                    <td>Level</td>
                    <td>Quantity</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($allUnits as $unitConnection){
                        $unit = Unit::findOne($unitConnection->unit);
                ?>
                    <tr>
                        <td><?= $unit->name ?></td>
                        <td><?= $unit->level ?></td>
                        <td><?= $unitConnection->count ?></td>
                        <td>
                            <?php
                                $newConnection = new UnitConnections();
                                $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form-horizontal'],]);
                                    echo $form->field($newConnection, 'count')->textInput()->label('Quantity');
                                    echo $form->field($newConnection, 'army')->hiddenInput(['value' => $army->id,])->label("");
                                    echo $form->field($newConnection, 'unit')->hiddenInput(['value' => $unitConnection->unit,])->label("");
                                    echo Html::submitButton("Remove", ['class' => 'btn btn-success']);
                                ActiveForm::end();
                            ?>
                        </td>
                    </tr>
                <?php
                    } //End foreach($allUnits)
                ?>
            </tbody>
        </table>
    <?php 
        } //End if(count($allUnits)) 
        else {
    ?>
        <div class='jumbotron'>
            <h2>You no one in your army!</h2>
        </div>
    <?php } //End else ?>

</div>
