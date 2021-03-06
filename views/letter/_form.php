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
$sCsrf = Yii::$app->request->csrfToken;

/*
jQuery("#mailtempl-mt_text")
    .redactor({
        "lang":"ru",
        "minHeight":400,
        "imageManagerJson":"/mailtempl/images-get",
        "imageUpload":"/mailtempl/image-upload",
        "replaceDivs":false,
        "plugins":["clips","fullscreen","imagemanager"],
        "uploadImageFields":{"_csrf":"WHVON0pMRWE/RzhmAwJ2DzQxK28eBhpTLQEGAxJ1F1ABPiB6Jg48EQ=="},
        "uploadFileFields":{"_csrf":"WHVON0pMRWE/RzhmAwJ2DzQxK28eBhpTLQEGAxJ1F1ABPiB6Jg48EQ=="},
        "imageUploadErrorCallback":function (response) { alert(response.error); }
    });

*/

$sJs = <<<EOT
var obArea = jQuery("#templatearea");
var oTextEditField = jQuery("#text-block-field");
var oImageEditField = jQuery("#image-block-field");
var textArea = jQuery("#text-edit-group");
var imageArea = jQuery("#image-edit-group");

var oLetterPreview = jQuery("#col-letter-preview"),
    oLetterTools = jQuery("#col-letter-tools");

oTextEditField
    .redactor({
        replaceDivs: false,
        lang: "ru",

        plugins:["imagemanager"],
        imageManagerJson: "/mailtempl/images-get",
        imageUpload: "/mailtempl/image-upload",
        uploadImageFields: {"_csrf":"{$sCsrf}"},
        uploadFileFields:{"_csrf":"{$sCsrf}"},
        imageUploadErrorCallback: function (response) { alert(response.error); },

        changeCallback: function() {
            obArea.templateeditor('setBlockData', this.code.get());
        }
    });

oImageEditField
    .redactor({
        replaceDivs: false,
        lang: "ru",
        plugins:["imagemanager"],
        imageManagerJson: "/mailtempl/images-get",
        imageUpload: "/mailtempl/image-upload",
        uploadImageFields: {"_csrf":"{$sCsrf}"},
        uploadFileFields:{"_csrf":"{$sCsrf}"},
        imageUploadErrorCallback: function (response) { alert(response.error); },
        changeCallback: function() {
            obArea.templateeditor('setBlockData', this.code.get());
        }
    });

obArea
    .templateeditor(
        {
            ontextselect: function(obText, isSelected) {
                var oRedactor = oTextEditField.parent(),
                    isRedactorExists = oRedactor.hasClass("redactor-box");

                if( isRedactorExists || !isSelected ) {
                    // тут мы убиваем редактор, так как не нашел способа это сделать по-другому
                    oTextEditField.redactor('code.set', '');
//                    var oBlock = isRedactorExists ? oRedactor.parent() : oRedactor;
//
//                    oTextArea = oTextArea.clone();
//                    if( isRedactorExists ) {
//                        oRedactor.remove();
//                        jQuery(".redactor-toolbar-tooltip").remove();
//                    }
//
//                    oBlock.append(oTextArea);
//                    oTextArea
//                        .html("")
//                        .show();
                }
                imageArea.hide();

                if( isSelected ) {
                    textArea.show();
                    oTextEditField.redactor('code.set', obArea.templateeditor('getBlockData'));
                    changeToolsPosition();
                }
                else {
                    textArea.hide();
                }
            },

            onimageselect: function(obImage, isSelected) {
                oImageEditField.redactor('code.set', '');
                textArea.hide();

                if( isSelected ) {
                    imageArea.show();
                    oImageEditField.redactor('code.set', obArea.templateeditor('getBlockData'));
                    changeToolsPosition();
                    imageArea.find('a[rel="image"]').trigger("click");
                }
                else {
                    imageArea.hide();
                }
            },
            sourcefield: "#{$sTextId}",
            textblockfield: "#text-block-field"
//            blockcontainer: ".block-container",
//            blocksarea: "#block-area",
//            blockselector: ".block-element"
        }
    );

var changeToolsPosition = function() {
    var offsetPreview = oLetterPreview.offset(),
        offsetTools = oLetterTools.offset(),
        nTop = jQuery(document).scrollTop(), // TODO: тут надо брать текущий выбранный элемент и его смещение сверху, а то после ресайза окна фигня получается
        nMenuBarHeigth = jQuery("#w1").height();

        if( offsetPreview.top == offsetTools.top ) {
            if( nTop > 0 ) {
                oLetterTools.css('padding-top', nTop - offsetPreview.top + nMenuBarHeigth);
            }
            else {
                oLetterTools.css('padding-top', 0);
            }
            jQuery(document).trigger("scroll");
        }
        else {
            oLetterTools.css('padding-top', 0);
            jQuery("html, body").animate({ scrollTop: jQuery(document).height() }, 500);
        }

};

jQuery(window).on("resize", function(event){ changeToolsPosition(); });

EOT;

$this->registerJs($sJs, View::POS_READY);

EdittemplateAsset::register($this);

$asset = \vova07\imperavi\Asset::register($this);
$asset->plugins = ['imagemanager'];
$asset->language = 'ru';

?>

<div class="letter-form">
    <div class="row">
        <div class="col-md-8" id="col-letter-preview">
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
        <div class="col-md-4" id="col-letter-tools">
            <div class="form-group" id="text-edit-group" style="display: none;">
                <label class="control-label" for="letter-let_subject">Изменение текста</label>
                <div class="clearfix"></div>
                <?= Html::textarea('textblock', '', ['id' => 'text-block-field']) ?>
                <div class="help-block"></div>
            </div>

            <div class="form-group" id="image-edit-group" style="display: none;">
                <label class="control-label" for="letter-let_subject">Изменение картинки</label>
                <div class="clearfix"></div>
                <?= '' // Html::a('Выбрать', '#', ['id' => 'image-select-link']) ?>
                <div class="clearfix"></div>
                <?= Html::textarea('imageblock', '', ['id' => 'image-block-field']) ?>
                <div class="help-block"></div>
            </div>
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
