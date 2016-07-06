<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Listgroup;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ListelementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список рассылки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listelement-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'le_id',
//            'le_createtime',
            'le_email:email',
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'le_fam',
                'value' => function ($model, $key, $index, $column) {
                    /** @var app\models\Listelement $model */
                    return Html::encode(
                        trim($model->le_fam . ' ' . $model->le_name . ' ' . $model->le_otch)
                    );
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => '_allgroups',
                'filter' => Listgroup::getList(),
                'value' => function ($model, $key, $index, $column) {
                    /** @var app\models\Listelement $model */
                    return Html::encode(
                        implode(', ', ArrayHelper::map($model->groups, 'lg_id', 'lg_name'))
                    );
                },
            ],
//            'le_fam',
//            'le_name',
            // 'le_otch',
            // 'le_org',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
            ],

        ],
    ]); ?>
</div>
