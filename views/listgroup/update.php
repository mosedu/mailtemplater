<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Listgroup */

$this->title = 'Изменение: ' . $model->lg_name;
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->lg_id, 'url' => ['view', 'id' => $model->lg_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listgroup-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
