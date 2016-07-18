<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LetterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Письма';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letter-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новое письмо', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Новое письмо из шаблона', ['mailtempl/index'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'let_id',
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'let_createtime',
                'headerOptions' => [
                    'style' => 'width: 200px;',
                ],
                'content' => function ($model, $key, $index, $column) {
                    $s = date('d.m.Y H:i:s', strtotime($model->let_createtime)) . "<br />\n";
                    if( !empty($model->let_sendtime) ) {
                        $s .= date('d.m.Y H:i:s', strtotime($model->let_sendtime));
                    }
                    else {
                        $s .= '-';
                    }
                    return $s;
                }
            ],
            'let_subject',
//            'let_createtime',
//            'let_sendtime',
//            'let_mt_id',
//            'let_text:ntext',
            // 'let_us_id',
            // 'let_send_id',
            // 'let_state',
            // 'let_send_num',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {prepare}', //  {delete}
                'buttonOptions' => [
                    'class' => 'btn btn-success',
                ],
                'buttons' => [
                    'prepare' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-envelope"></span>', $url, ['class' => 'btn btn-success', 'title' => 'Отправить', ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
