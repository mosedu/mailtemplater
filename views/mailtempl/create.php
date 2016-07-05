<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mailtempl */

$this->title = 'Create Mailtempl';
$this->params['breadcrumbs'][] = ['label' => 'Mailtempls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailtempl-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
