<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Kingdom;

/* @var $this yii\web\View */
/* @var $model app\models\Kingdom */

$this->title = "Kingdom";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kingdom-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Army', ['/army/status',], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Gold',
                'value' => round($model->points * Kingdom::POINTS_TO_GOLD_SCALAR, 0),
            ],
            [
                'label' => 'Economy',
                'value' => round($model->economy, 0),
            ],
            [
                'label' => 'Loyalty',
                'value' => round($model->loyalty, 0),
            ],
            [
                'label' => 'Stability',
                'value' => round($model->stability, 0),
            ],
            [
                'label' => 'Unrest',
                'value' => round($model->unrest, 0),
            ],
        ],
    ]) ?>

</div>
