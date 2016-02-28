<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Kingdom */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kingdom-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user')->textInput() ?>

    <?= $form->field($model, 'points')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'economy')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'loyalty')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stability')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'unrest')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
