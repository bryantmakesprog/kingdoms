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
$allUnits = Unit::find()->all();

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

$this->title = "Army - Recruit";
$this->params['breadcrumbs'][] = ['label' => 'Kingdom', 'url' => ['/kingdom/status']];
$this->params['breadcrumbs'][] = ['label' => 'Army', 'url' => ['/army/status']];
$this->params['breadcrumbs'][] = "Recruit";
?>
<div class="army-view">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a('Roster', ['/army/roster',], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php if(count($allUnits)) { ?>
        <table class='table table-hover'>
            <thead>
                <tr>
                    <td>Unit</td>
                    <td>Level</td>
                    <td>Description</td>
                    <td>Cost</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($allUnits as $unit){
                ?>
                    <tr>
                        <td><?= $unit->name ?></td>
                        <td><?= $unit->level ?></td>
                        <td><?= $unit->description ?></td>
                        <td><?= $unit->getCostInGold() ?></td>
                        <!--Purchase Button-->
                        <td>
                            <?php
                                $unitConnection = new UnitConnections();
                                $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form-horizontal'],]);
                                    echo $form->field($unitConnection, 'count')->textInput()->label('Quantity');
                                    echo $form->field($unitConnection, 'army')->hiddenInput(['value' => $army->id,])->label("");
                                    echo $form->field($unitConnection, 'unit')->hiddenInput(['value' => $unit->id,])->label("");
                                    echo Html::submitButton("Purchase", ['class' => 'btn btn-success']);
                                ActiveForm::end();
                            ?>
                        </td>
                    </tr>
                <?php
                    } //End foreach($allUnits)
                ?>
            </tbody>
        </table>
    <?php } //End if(count($allUnits)) ?>

</div>
