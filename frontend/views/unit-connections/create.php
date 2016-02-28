<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UnitConnections */

$this->title = 'Create Unit Connections';
$this->params['breadcrumbs'][] = ['label' => 'Unit Connections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-connections-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
