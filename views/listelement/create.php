<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Listelement */

$this->title = 'Создать';
$this->params['breadcrumbs'][] = ['label' => 'Список рассылки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listelement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
