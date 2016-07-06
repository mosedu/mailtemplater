<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model app\models\Mailtempl */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mailtempl-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= '' // $form->field($model, 'mt_createtime')->textInput() ?>

    <?= $form->field($model, 'mt_name')->textInput(['maxlength' => true]) ?>

    <?= '' // $form->field($model, 'mt_text')->textarea(['rows' => 6]) ?>

    <?= $form
        ->field($model, 'mt_text')
        ->widget(Widget::className(), [
            'settings' => [
                'lang' => 'ru',
                'minHeight' => 400,
                'imageManagerJson' => Url::to(['/mailtempl/images-get']),
                'imageUpload' => Url::to(['/mailtempl/image-upload']),
                'plugins' => [
                    'clips',
                    'fullscreen',
                    'imagemanager',
                ],
            ]
        ])
        ->hint('Возможные поля для вставки из списка рассылки: *|email|*, *|fam|*, *|name|*, *|otch|*, *|org|*, *|fullname|*, *|usergroups|*'); ?>

    <div class="form-group">
        <?= Html::submitButton(
            $model->isNewRecord ? 'Создать' : 'Сохранить',
            [
                'class' => 'btn btn-success',
            ]
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
