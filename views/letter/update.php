<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Letter */

$this->title = $model->isNewRecord ? 'Новая рассылка' : 'Изменение рассылки: ' . $model->let_subject . ' (' . date('d.m.Y H:i', strtotime($model->let_createtime)) . ')';
$this->params['breadcrumbs'][] = ['label' => 'Расылки', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->let_subject, 'url' => ['view', 'id' => $model->let_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letter-update">

    <h1><?= '' // Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
