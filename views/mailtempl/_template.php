<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model app\models\Mailtempl */
/* @var $widget ListView */
/* @var $key string|integer mixed, the key value associated with the data item */
/* @var $index integer the zero-based index of the data item in the items array returned by $dataProvider */

$sHtml = $model->mt_text;
$sHtml = preg_replace('|(font-size:\\s*?\\d+\\s*?px)|', '', $sHtml); // 'font-size: 3px'
$sHtml = preg_replace('|(width:\\s*?\\d+\\s*?px)|', 'width: 100%', $sHtml); // 'font-size: 3px'
$sHtml = preg_replace('|<a[^>]+?>([^<]*?)</a>|', '\\1', $sHtml); // 'font-size: 3px'

?>
<div class="mailtempl-element col-md-3">
    <div class="template-preview-html"><?= Html::a(
            $sHtml,
            [
                'mailtempl/view',
                'id' => $model->mt_id,
            ],
            [
                'class' => 'showinmodal',
                'title' => 'Просмотр ' . Html::encode($model->mt_name),
            ]
        ) ?></div>
    <div class="template-preview-buttons row">
        <div class="col-md-12">
            <?= Html::a(
                '<span class="glyphicon glyphicon-eye-open"></span>',
                [
                    'mailtempl/view',
                    'id' => $model->mt_id,
                ],
                [
                    'class' => 'btn btn-success showinmodal',
                    'title' => 'Просмотр ' . Html::encode($model->mt_name),
                ]
            ) ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-pencil"></span>',
                [
                    'mailtempl/update',
                    'id' => $model->mt_id,
                ],
                [
                    'class' => 'btn btn-success',
                    'title' => 'Изменить ' . Html::encode($model->mt_name),
                ]
            ) ?>
            <?= Html::a(
                '<span class="glyphicon glyphicon-envelope"></span>',
                [
                    'letter/create',
                    'templateid' => $model->mt_id,
                ],
                [
                    'class' => 'btn btn-success pull-right',
                    'title' => 'Создать письмо по шаблону ' . Html::encode($model->mt_name),
                ]
            ) ?>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
