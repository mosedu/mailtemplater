<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use vova07\imperavi\Widget;
use app\models\Listgroup;

/* @var $this yii\web\View */
/* @var $model app\models\TemplatesendForm */
/* @var $template app\models\Mailtempl */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mailtempl-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'groupid')->dropDownList(Listgroup::getList()) ?>

    <?= $form->field($model, 'templateid')->hiddenInput() ?>

    <div class="row">
        <div class="col-xs-12">
            <?= $template->mt_text ?>
        </div>
        <div class="clearfix"></div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <div class="form-group" style="margin-top: 24px;">
                <?= Html::submitButton(
                    'Отправить',
                    [
                        'class' => 'btn btn-success',
                    ]
                ) ?>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
