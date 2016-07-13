<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\Tabs;
use app\assets\EdittemplateAsset;
use yii\jui\JuiAsset;

$this->title = 'Редактирование шаблона';
$this->params['breadcrumbs'][] = $this->title;

EdittemplateAsset::register($this);
JuiAsset::register($this);

$sBlock1 = <<<EOT
<div class="block-container" style="width: 100%; border: 1px solid #999999;">
    <table>
        <tr>
            <td style="width: 30%; vertical-align: top;">
                <div class="image-block">
                    <img src="/tmp-local/no-image.png" />
                </div>
            </td>
            <td style="width: 5%;"></td>
            <td style="width: 30%; vertical-align: top;" rowspan="2">
                <div class="text-block">
                    <p>Some text for text block.</p>
                </div>
            </td>
            <td style="width: 5%;"></td>
            <td style="width: 30%; vertical-align: top;">
                <div class="image-block">
                    <img src="/tmp-local/no-image.gif" />
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 30%; vertical-align: top;">
                <div class="text-block">
                    <p>Image title.</p>
                </div>
            </td>
            <td style="width: 5%;"></td>
            <td style="width: 5%;"></td>
            <td style="width: 30%; vertical-align: top;">
                <div class="text-block">
                    <p style="text-align: center; font-weight: bold; font-size: 1.2em;">Image title.</p>
                    <p>Image text.</p>
                </div>
            </td>
        </tr>
    </table>
</div>
EOT;

$sBlock2 = <<<EOT
<div class="block-container" style="width: 100%; border: 1px solid #999999;">
    <div class="image-block" style="width: 100%;">
        <img src="/tmp-local/no-image.png" />
    </div>
    <div class="text-block" style="width: 100%;">
        <p>Some text for text block.</p>
    </div>
</div>
EOT;

$sBlock3 = <<<EOT
<div class="block-container" style="width: 100%; border: 1px solid #999999;">
    <div class="image-block" style="width: 100%;">
        <img src="/tmp-local/no-image.png" style="width: 50%;" />
    </div>
    <div class="text-block" style="width: 100%;">
        <p>Some text for text block.</p>
    </div>
</div>
EOT;
/*

?>

<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <div style="width: 450px; margin: 20px;" ><?= $sBlock1 ?></div>

    <div style="width: 240px; margin: 20px; clear: both;" ><?= $sBlock1 ?></div>

    <div style="width: 360px; margin: 20px; clear: both; float: left;" ><?= $sBlock2 ?></div>
    <div style="width: 360px; margin: 20px; float: left;" ><?= $sBlock2 ?></div>
    <div style="width: 360px; margin: 20px; float: left;" ><?= $sBlock3 ?></div>

</div>
*/
$sJs = <<<EOT
jQuery("#templatearea")
    .templateeditor(
        {
            blockcontainer: ".block-container",
            blocksarea: "#block-area",
            blockselector: ".block-element"
        }
    );
EOT;

$this->registerJs($sJs, View::POS_READY);

$sHtml = <<<EOT
    <div style="width: 450px; margin: 20px;" >{$sBlock1}</div>

    <div style="width: 240px; margin: 20px; clear: both;" >{$sBlock1}</div>

    <div style="width: 360px; margin: 20px; clear: both; float: left;" >{$sBlock2}</div>
    <div style="width: 360px; margin: 20px; float: left;" >{$sBlock2}</div>
    <div style="width: 360px; margin: 20px; float: left;" >{$sBlock3}</div>

EOT;

$aBlocks = compact('sBlock1', 'sBlock2', 'sBlock3');
//echo nl2br(print_r($aBlocks, true));
$sBlocks = array_reduce(
    $aBlocks,
    function($carry, $el) {
        $carry .= '<div class="col-md-6"><div class="block-element">'.$el.'</div></div>';
        return $carry;
    },
    ''
);

?>

<div class="template-editor">
    <div class="row">
        <div class="col-md-8">
            <div>Шаблон для редактирования:</div>
            <div id="templatearea" style="min-height: 100px; border: 1px solid #cccccc; width: 600px;"></div>
            <?= '' // $sHtml ?>
            <div class="clearfix"></div>
        </div>
        <div class="col-md-4">
            <?php
            echo Tabs::widget([
                'items' => [
                    [
                        'label' => 'Блоки',
                        'content' => '<div class="col-md-12">Тут список блоков для перетаскивания</div>' . $sBlocks,
                        'active' => true,
                        'options' => [
                            'id' => 'block-area',
                        ],
                    ],
                    [
                        'label' => 'Редактор',
                        'content' => 'Тут будет редактор для картинок и текста',
//                        'headerOptions' => [],
//                        'options' => [
//                            'id' => 'myveryownID',
//                        ],
                    ],
//                    [
//                        'label' => 'Example',
//                        'url' => 'http://www.example.com',
//                    ],
//                    [
//                        'label' => 'Dropdown',
//                        'items' => [
//                            [
//                                'label' => 'DropdownA',
//                                'content' => 'DropdownA, Anim pariatur cliche...',
//                            ],
//                            [
//                                'label' => 'DropdownB',
//                                'content' => 'DropdownB, Anim pariatur cliche...',
//                            ],
//                        ],
//                    ],
                ],
            ]);
            ?>
        </div>
    </div>
</div>
