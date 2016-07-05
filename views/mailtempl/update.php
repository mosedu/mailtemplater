<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mailtempl */

$this->title = 'Update Mailtempl: ' . $model->mt_id;
$this->params['breadcrumbs'][] = ['label' => 'Mailtempls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->mt_id, 'url' => ['view', 'id' => $model->mt_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mailtempl-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
