<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Listelement */

$this->title = 'Изменить: ' . $model->le_email;
$this->params['breadcrumbs'][] = ['label' => 'Список рассылки', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->le_id, 'url' => ['view', 'id' => $model->le_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listelement-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
