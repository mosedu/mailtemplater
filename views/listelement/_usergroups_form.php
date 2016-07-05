<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\View;
use yii\helpers\ArrayHelper;

use kartik\select2\Select2;

use app\models\Listgroup;

/* @var $this yii\web\View */
/* @var $model app\models\Userdata */
/* @var $form yii\widgets\ActiveForm */
/* @var $index int */
/* @var $startindex int */

$sId = $index;

?>

    <div class="col-sm-3">
        <?= $form
            ->field($model, '[' . $sId . ']hostid', ['template' => "{input}\n{hint}\n{error}"])
            ->dropDownList($aResource)
        //            ->widget(Select2::classname(), $aResource) ?>
    </div>

    <div class="col-sm-1">&nbsp;</div>

    <div class="col-sm-7">
        <?= $form
            ->field($model, '[' . $sId . ']groups', ['template' => "{input}\n{hint}\n{error}"])
            ->listBox(
                ArrayHelper::map(
                    Group::getAllTreeList(),
                    'group_id',
                    'group_title'
                ), // Group::getList(),
                [
                    'multiple'=>'multiple',
                    'options' => ArrayHelper::map(
                        Group::getAllTreeList(),
                        'group_id',
                        function($ob){
                            return [
                                'label' => $ob->group_title,
                                'data-hostid' => implode(',', ArrayHelper::map($ob->hosts, 'host_id', 'host_id')),
                                'data-level' => $ob->group_level,
                            ];
                        }
                    ),
                ]
            )
        ?>
    </div>

    <div class="col-sm-1">
        <?= Html::a(
            Html::tag('span', '', ['class' => 'glyphicon glyphicon-remove']),
            '',
            [
                'class' => 'btn btn-danger remove-resource',
            ]
        ) ?>
        <?= $form
            ->field($model, '[' . $sId . ']userid', ['template' => "{input}\n{error}"])
            ->hiddenInput() ?>
    </div>

<?= '' //$form->field($model, 'udat_res_id')->textInput() ?>
    <div class="clearfix"></div>
    <!-- /div -->

<?php
if( $startindex != $index ) {
    $sIdResource = Html::getInputId($model, '[' . $sId . ']hostid');
    $sIdPermission = Html::getInputId($model, '[' . $sId . ']groups');
    $sParamName = 'select2_param';
    $sJs = <<<EOT
// jQuery("#{$sIdResource}").select2();
var {$sParamName} = {"allowClear":true,"theme":"krajee","placeholder":"Выберите из списка ...","language":"ru_Ru","width":"100%"};
// console.log("Select2: {$sIdPermission}");
jQuery("#{$sIdPermission}").select2({$sParamName});
EOT;
    $this->registerJs($sJs, View::POS_READY, 'select2_' . $index);
}
