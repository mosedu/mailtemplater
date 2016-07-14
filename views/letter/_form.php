<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\assets\EdittemplateAsset;
use yii\web\View;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model app\models\Letter */
/* @var $form yii\widgets\ActiveForm */

$sTextId = Html::getInputId($model, 'let_text');
$sJs = <<<EOT
jQuery("#templatearea")
    .templateeditor(
        {
            ontextselect: function(shtml) {
                console.log('ontextselect: ' + shtml);
                jQuery("#text-block-field")
//                    .redactor('destroy')
                    .html(shtml)
                    .redactor();
            },
            sourcefield: "#{$sTextId}",
            textblockfield: "#text-block-field"
//            blockcontainer: ".block-container",
//            blocksarea: "#block-area",
//            blockselector: ".block-element"
        }
    );
EOT;

$this->registerJs($sJs, View::POS_READY);

EdittemplateAsset::register($this);

\vova07\imperavi\Asset::register($this);

?>

<div class="letter-form">
    <div class="row">
    <div class="col-md-8">
        <div>Шаблон для редактирования:</div>
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'let_subject')->textInput() ?>
        <div style="display: none;">
            <?= $form->field($model, 'let_text')->textarea(['rows' => 6]) ?>
        </div>

        <div id="templatearea" style="min-height: 100px; border: 1px solid #cccccc; padding: 10px;"></div>
        <?= '' // $sHtml ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', [ 'class' => 'btn btn-success', ]) ?>
        </div>

        <?php ActiveForm::end(); ?>
        <div class="clearfix"></div>
    </div>
    <div class="col-md-4">
        <?= Html::textarea('textblock', '', ['id' => 'text-block-field']) ?>
    </div>
    </div>


    <?php //$form = ActiveForm::begin(); ?>

    <?= '' // $form->field($model, 'let_createtime')->textInput() ?>

    <?= '' // $form->field($model, 'let_sendtime')->textInput() ?>

    <?= '' // $form->field($model, 'let_mt_id')->textInput() ?>

    <?= '' // $form->field($model, 'let_subject')->textInput() ?>
    <?= '' // $form->field($model, 'let_text')->textarea(['rows' => 6]) ?>

    <?= '' // $form->field($model, 'let_us_id')->textInput() ?>

    <?= '' // $form->field($model, 'let_send_id')->textInput() ?>

    <?= '' // $form->field($model, 'let_state')->textInput() ?>

    <?= '' // $form->field($model, 'let_send_num')->textInput() ?>

<!--    <div class="form-group">-->
<!--        --><?= '' // Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', [ 'class' => 'btn btn-success', ]) ?>
<!--    </div>-->

    <?php // ActiveForm::end(); ?>

</div>
