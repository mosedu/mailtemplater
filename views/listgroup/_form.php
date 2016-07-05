<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Listgroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="listgroup-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= '' // $form->field($model, 'lg_createtime')->textInput() ?>

    <?= $form->field($model, 'lg_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
