<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mailtempl */

$this->title = 'Изменить ' . $model->mt_name;
$this->params['breadcrumbs'][] = ['label' => 'Шаблоны', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->mt_id, 'url' => ['view', 'id' => $model->mt_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailtempl-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
