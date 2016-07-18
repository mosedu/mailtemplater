<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Response;
use yii\httpclient\Client;

use app\models\Letter;
use app\models\LetterSearch;
use app\models\Mailtempl;
use app\components\EmailCreator;

/**
 * LetterController implements the CRUD actions for Letter model.
 */
class LetterController extends Controller
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
     * Lists all Letter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LetterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Letter model.
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
     * Creates a new Letter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        return $this->actionUpdate(0);
//        $model = new Letter();
//
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->let_id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
    }

    /**
     * Updates an existing Letter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id = 0)
    {
        if( $id == 0 ) {
            $model = new Letter();
            $templateId = intval(Yii::$app->request->getQueryParam('templateid', 0));
            $obTemplate = Mailtempl::findOne($templateId);
            if( $obTemplate !== null ) {
                $model->let_text = $obTemplate->mt_text;
            }
        }
        else {
            $model = $this->findModel($id);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->let_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Letter model.
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
     *
     * Выдача текстов письма
     *
     * @param $id
     * @throws NotFoundHttpException
     */
    public function actionPrepare($id) {
        $model = $this->findModel($id);
        $oEmail = new EmailCreator($model->let_text);
        $aImg = $oEmail->getImages();
        $aNewImg = [];
        $nCount = 0;
        foreach($aImg As $k=>$v) {
            $nCount++;
            $aNewImg[$k] = ':image' . $nCount;
        }
        $oEmail->replaceImages($aNewImg);

        $aText = $oEmail->getText();
        $aText['_csrf'] = Yii::$app->request->csrfToken;

        $s = nl2br(print_r($oEmail->getText(), true)); // str_replace(' ', '&nbsp;',)
        $sUrl = Url::to(['letter/getdata'], true);

        // тут отправка запроса на адрес отправки - в теле письма тексты, файлы
        $oHttpClient = new Client();
        $oRequest = $oHttpClient->createRequest()
            ->setMethod('post')
            ->setData($aText)
            ->setUrl($sUrl);

        $nCount = 0;
        foreach($aImg As $k=>$v) {
            $oRequest->addFile('file['.($nCount++).']', $v);
        }

        $oRespond = $oRequest->send();

        if( $oRespond->isOk ) {
            $data = $oRespond->data;
        }
        else {
            $data = $oRespond;
        }

        $sContent = $oRespond->content;
//            ->addFile('file', '/path/to/source/file.jpg')
//            ->send();

        return $this->renderContent(nl2br($sUrl . " ({$sContent})\n" . print_r($data, true)));
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($action->id == 'getdata') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    /**
     *
     */
    public function actionGetdata() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'files' => isset($_FILES['file']) ? count($_FILES['file']['name']) : 0,
            'fields' => count($_POST),
        ];
//        Yii::info("actionGetdata():\n_POST = " . print_r($_POST, true) . "\n_FILES = " . print_r($_FILES, true));
    }

    /**
     * Finds the Letter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Letter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Letter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
