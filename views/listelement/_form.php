<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Listelement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="listelement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= '' // $form->field($model, 'le_createtime')->textInput() ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'le_email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'le_org')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'le_fam')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'le_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'le_otch')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
