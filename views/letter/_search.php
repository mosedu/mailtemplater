<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LetterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="letter-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'let_id') ?>

    <?= $form->field($model, 'let_createtime') ?>

    <?= $form->field($model, 'let_sendtime') ?>

    <?= $form->field($model, 'let_mt_id') ?>

    <?= $form->field($model, 'let_text') ?>

    <?php // echo $form->field($model, 'let_us_id') ?>

    <?php // echo $form->field($model, 'let_send_id') ?>

    <?php // echo $form->field($model, 'let_state') ?>

    <?php // echo $form->field($model, 'let_send_num') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
