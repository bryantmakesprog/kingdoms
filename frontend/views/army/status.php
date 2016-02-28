<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Army;
use app\models\Kingdom;

/* @var $this yii\web\View */
/* @var $model app\models\Army */

$this->title = "Army";
$this->params['breadcrumbs'][] = ['label' => 'Kingdom', 'url' => ['/kingdom/status']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="army-view">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <p>
        <?= Html::a('Roster', ['/army/roster',], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Recruit', ['/army/recruit',], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php
        $stats = Army::getStatsById($model->id);
        echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Level',
                        'value' => $stats['level'],
                    ],
                    [
                        'label' => 'Size',
                        'value' => $stats['count'],
                    ],
                    [
                        'label' => 'Offense',
                        'value' => $stats['offense'],
                    ],
                    [
                        'label' => 'Offense (Ranged)',
                        'value' => $stats['offense_ranged'],
                    ],
                    [
                        'label' => 'Defense',
                        'value' => $stats['defense'],
                    ],
                    [
                        'label' => 'Cost-to-Maintain',
                        'value' => round($stats['consumption'] * Kingdom::POINTS_TO_GOLD_SCALAR, 0),
                    ],
                ],
            ]);
    ?>

</div>
