<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Army */

$this->title = 'Create Army';
$this->params['breadcrumbs'][] = ['label' => 'Armies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="army-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
