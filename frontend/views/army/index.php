<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Armies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="army-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Army', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'user',
            [
                'label' => 'Username',
                'value' => function ($data) {
                    return User::findOne($data->user)->username; // $data['name'] for array data, e.g. using SqlDataProvider.
                    },
            ],
            'kingdom',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
