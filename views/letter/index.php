<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LetterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Letters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="letter-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Letter', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'let_id',
            'let_createtime',
            'let_sendtime',
            'let_mt_id',
            'let_text:ntext',
            // 'let_us_id',
            // 'let_send_id',
            // 'let_state',
            // 'let_send_num',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
