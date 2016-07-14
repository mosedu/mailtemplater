<?php

namespace app\controllers;

use Yii;

use app\models\Mailtempl;
use app\models\MailtemplSearch;
use app\models\TemplatesendForm;
use app\models\ListelementSearch;
use app\models\Listelement;
use app\components\MailCreator;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use vova07\imperavi\actions\GetAction;
use vova07\imperavi\actions\UploadAction;

/**
 * MailtemplController implements the CRUD actions for Mailtempl model.
 */
class MailtemplController extends Controller
{
    public function actions()
    {
        return [
            'images-get' => [
                'class' => GetAction::className(),
                'url' => 'http://'.$_SERVER['HTTP_HOST'].'/images/', // Directory URL address, where files are stored.
                'path' => '@webroot/images', // Or absolute path to directory where files are stored.
                'type' => GetAction::TYPE_IMAGES,
            ],
            'image-upload' => [
                'class' => UploadAction::className(),
                'url' => 'http://'.$_SERVER['HTTP_HOST'].'/images/', // Directory URL address, where files are stored.
                'path' => '@webroot/images' // Or absolute path to directory where files are stored.
            ],
        ];
    }

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
     * Lists all Mailtempl models.
     * @return mixed
     */
    public function actionList()
    {
        $searchModel = new MailtemplSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all Mailtempl models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->actionList();
//        $searchModel = new MailtemplSearch();
//        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//
//        return $this->render('index', [
//            'searchModel' => $searchModel,
//            'dataProvider' => $dataProvider,
//        ]);
    }

    /**
     * Displays a single Mailtempl model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if( Yii::$app->request->isAjax ) {
            return $this->renderPartial('view', [
                'model' => $model,
                'onlytemplate' => true,
            ]);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Mailtempl model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Mailtempl();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', ]);
//            return $this->redirect(['view', 'id' => $model->mt_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Mailtempl model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', ]);
//            return $this->redirect(['view', 'id' => $model->mt_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     *
     * @param integer $id
     * @return mixed
     */
    public function actionCreatemail($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['list', ]);
//            return $this->redirect(['view', 'id' => $model->mt_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Mailtempl model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
//        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param int $id
     * @return string|\yii\web\Response
     */
    public function actionSend($id)
    {
        $model = new TemplatesendForm();
        $template = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->session->setFlash('success', 'Отправлено писем: ' . $this->sendMails($template, $model));
            return $this->redirect(['index', ]);
//            return $this->redirect(['view', 'id' => $model->mt_id]);
        }

        $model->templateid = $template->mt_id;

        return $this->render('send', [
            'model' => $model,
            'template' => $template,
        ]);
    }

    /**
     * @param Mailtempl $template
     * @param TemplatesendForm $model
     * @return int
     */
    public function sendMails($template, $model) {
        $findModel = new ListelementSearch();

        $dataProvider = $findModel->search([
            $findModel->formName() => [
                '_allgroups' => $model->groupid,
            ]
        ]);
        $dataProvider->prepare();
        $nMaxCount = $dataProvider->pagination->totalCount;
        $nPageCount = $dataProvider->pagination->pageCount;
        $aMessages = [];
        for($page = 0; $page < $nPageCount; $page++) {
            $dataProvider->pagination->setPage($page);
            $dataProvider->refresh();

            foreach($dataProvider->getModels() As $oSubscriber) {
                $aMessages[] = MailCreator::createMail($template, $model, $oSubscriber);
            }
        }

        if( count($aMessages) > 0 ) {
            Yii::$app->mailer->sendMultiple($aMessages);
        }

        return $nMaxCount;
    }


    /**
     * Finds the Mailtempl model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mailtempl the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mailtempl::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
