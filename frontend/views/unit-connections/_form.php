<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UnitConnections */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="unit-connections-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'army')->dropDownList($model->getArmyOptions()) ?>

    <?= $form->field($model, 'unit')->dropDownList($model->getUnitOptions()) ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
