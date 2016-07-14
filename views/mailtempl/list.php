<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ListView;
use app\assets\EdittemplateCssAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MailtemplSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Шаблоны';
$this->params['breadcrumbs'][] = $this->title;

EdittemplateCssAsset::register($this);

?>
<div class="mailtempl-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить шаблон', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= ListView::widget([
        'itemView' => '_template',
        'layout' => "{items}\n{pager}", // {sorter}{summary}
        'emptyText' => 'Пока шаблонов не введено',
        'dataProvider' => $dataProvider,
//        'itemOptions' => [],
    ]) ?>
    <?= '' /* GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'mt_id',
//            'mt_createtime',
            'mt_name',
            'mt_text:raw',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {send}',
                'buttonOptions' => ['class' => 'btn btn-success',],
                'buttons' => [
                    'send' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-envelope"></span>', $url, ['class' => 'btn btn-success', 'title' => 'Отправить', ]);
                    },
                ],
            ],
        ],

    ]); */ ?>
</div>
