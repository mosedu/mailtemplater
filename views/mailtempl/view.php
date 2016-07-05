<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Mailtempl */

$this->title = $model->mt_id;
$this->params['breadcrumbs'][] = ['label' => 'Mailtempls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailtempl-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->mt_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->mt_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'mt_id',
            'mt_createtime',
            'mt_name',
            'mt_text:ntext',
        ],
    ]) ?>

</div>
