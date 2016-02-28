<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Army;

/* @var $this yii\web\View */
/* @var $model app\models\Army */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Armies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="army-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
        $stats = Army::getStatsById($model->id);
        echo DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    'user',
                    'kingdom',
                    [
                        'label' => 'Size',
                        'value' => $stats['count'],
                    ],
                    [
                        'label' => 'Level',
                        'value' => $stats['level'],
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
                ],
            ]);
    ?>

</div>
