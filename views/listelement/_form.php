<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\Listgroup;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Listelement */
/* @var $form yii\widgets\ActiveForm */

$groupsData = Listgroup::getList();

//$model->_allgroups =
//    ArrayHelper::map(
//        $model->groups,
//        'lg_id',
//        'lg_id'
//    )
//;

$model->_allgroups = array_keys(
    ArrayHelper::map(
        $model->groups,
        'lg_id',
        'lg_name'
    )
);

?>

<div class="listelement-form">

    <?php $form = ActiveForm::begin([
        'id' => 'listelement-form',
        'enableAjaxValidation' => true,
        'enableClientValidation' => false,
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'validateOnBlur' => false,
        'validateOnType' => false,
    ]); ?>

    <?= '' // 'model->_allgroups: ' . nl2br(print_r($model->_allgroups, true)) // $form->field($model, 'le_createtime')->textInput() ?>

    <div class="row">
        <div class="col-sm-4">
            <?= $form->field($model, 'le_email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, 'le_org')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-sm-4">
            <?= $form->field($model, '_allgroups')->widget(Select2::classname(), [
                'data' => $groupsData,
                'language' => 'ru',
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'placeholder' => 'Выберите группу',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true,
                ],
            ]) ?>
            <?= '' // $form->field($model, '_allgroups')->dropDownList($groupsData, ['multiple' => true,]) ?>
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
