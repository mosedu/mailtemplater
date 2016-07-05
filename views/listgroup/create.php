<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Listgroup */

$this->title = 'Новая группа';
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listgroup-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
