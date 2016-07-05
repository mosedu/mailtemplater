<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MailtemplSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mailtempl-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'mt_id') ?>

    <?= $form->field($model, 'mt_createtime') ?>

    <?= $form->field($model, 'mt_name') ?>

    <?= $form->field($model, 'mt_text') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
