<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Listelement */

$this->title = $model->le_id;
$this->params['breadcrumbs'][] = ['label' => 'Listelements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listelement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->le_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->le_id], [
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
            'le_id',
            'le_createtime',
            'le_email:email',
            'le_fam',
            'le_name',
            'le_otch',
            'le_org',
        ],
    ]) ?>

</div>
