<?php

namespace app\controllers;

use Yii;
use app\models\Listelement;
use app\models\ListelementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * ListelementController implements the CRUD actions for Listelement model.
 */
class ListelementController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Listelement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ListelementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Listelement model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Listelement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        return $this->actionUpdate(0);

//        $model = new Listelement();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['index']);
////            return $this->redirect(['view', 'id' => $model->le_id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
    }



    /**
     * Updates an existing Listelement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if( $id == 0 ) {
            $model = new Listelement();
        }
        else {
            $model = $this->findModel($id);
        }

        if( Yii::$app->request->isAjax ) {
            if( $model->load(Yii::$app->request->post()) ) {
//                $aErr = $this->validateModel($model);
                $aErr = ActiveForm::validate($model);
                Yii::$app->response->format = Response::FORMAT_JSON;
                return $aErr;
            }
            else {
//                return $this->renderAjax(
//                    '_formedit-group',
//                    [
//                        'model' => $model,
////                        'uid' => $model->us_id,
//                        'groupcollection' => $this->getParentGroupCollection(),
//                    ]
//                );
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            $model->saveGroups();
            $model->saveGrouptags();
            return $this->redirect(['index']);
//            return $this->redirect(['view', 'id' => $model->le_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param Listelement $model
     * @return array
     */
//    public function validateModel($model) {
//        $aRes = [];
//        $id = $model->le_id;
//        $aValidate = ActiveForm::validate($model);
//        $result = [];
////        $result = $this->getBehavior('validatePermissions')->validateData();
////        $data = $this->getBehavior('validatePermissions')->getData();
////        if( count($data['data']) == 0 ) {
////            $sId = Html::getInputId($model, 'us_useopenpas');
////
////            if( !isset($aValidate[$sId]) ) {
////                $aValidate[$sId] = [];
////            }
////            $aValidate[$sId][] = 'Необходимо указать группы пользователю.';
////        }
//
//        Yii::info('validateModel('.$id.'): return json ' . print_r($aValidate, true));
//        $aRes = array_merge($aValidate, $result);
//        if( count($aRes) == 0 ) {
//            $bNew = $model->isNewRecord;
//
////            if( $model->addTo($this->parent) ) {
////                $data = $this->getBehavior('validatePermissions')->getData();
////                $data = $model->parseGroups($data['data']);
////                $model->saveGroups($data);
////                if (!$bNew) {
////                    // чтоба при изменении записи отработалось событие по проверке изменения Групп
////                    unset($model->usergroupid);
////                    $model->trigger(User::EVENT_AFTER_UPDATE, new AfterSaveEvent([]));
////                }
////            }
////            else {
////                $aRes = $this->getBehavior('validatePermissions')->makeError($model, 'us_fullname', 'Database error');
////            }
//        }
//        return $aRes;
//    }

    /**
     * Deletes an existing Listelement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Listelement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Listelement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Listelement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
