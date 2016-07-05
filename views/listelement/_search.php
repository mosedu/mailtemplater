<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ListelementSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="listelement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'le_id') ?>

    <?= $form->field($model, 'le_createtime') ?>

    <?= $form->field($model, 'le_email') ?>

    <?= $form->field($model, 'le_fam') ?>

    <?= $form->field($model, 'le_name') ?>

    <?php // echo $form->field($model, 'le_otch') ?>

    <?php // echo $form->field($model, 'le_org') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
