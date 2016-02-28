<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

use app\models\Army;

/* @var $this yii\web\View */
/* @var $model app\models\Army */

$this->title = "Army";
$this->params['breadcrumbs'][] = ['label' => 'Armies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="army-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
                ],
            ]);
    ?>

</div>
