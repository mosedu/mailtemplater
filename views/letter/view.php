<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Letter */

$this->title = $model->let_subject;
$this->params['breadcrumbs'][] = ['label' => 'Письма', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letter-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!-- p>
        <?= '' /* Html::a('Update', ['update', 'id' => $model->let_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->let_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */ ?>
    </p -->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'let_id',
//            'let_createtime',
//            'let_sendtime',
//            'let_mt_id',
            'let_text:raw',
//            'let_us_id',
//            'let_send_id',
//            'let_state',
//            'let_send_num',
        ],
    ]) ?>

</div>
