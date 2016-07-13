<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\MailtemplSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Шаблоны';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mailtempl-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить шаблон', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
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

    ]); ?>
</div>
