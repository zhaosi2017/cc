<?php

namespace app\modules\home\controllers;

use app\modules\home\models\Potato;
use app\modules\home\models\User;
use Yii;
use app\controllers\GController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
/**
 * Class PotatoController.
 *
 * 对接第三方服务potato相关业务.
 */
class PotatoController extends GController
{

    public $enableCsrfValidation = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'bind-potato', 'unbundle-potato'],
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    echo 'You are not allowed to access this page!';
                }
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 默认入口地址.
     */
    public function actionIndex()
    {
        try{
            // $postData = Yii::$app->request->bodyParams;
            $postData = @file_get_contents('php://input');
            $postData = json_decode($postData, true);
            $potato = new Potato();
            $message = isset($postData['result']) ? $postData['result'] : array();
            $potato->potatoUid = isset($message['sender_id']) ? $message['sender_id'] : $message['user_id'];

            // 如果是用户第一次关注该机器人，发送欢迎信息,并发送内联快捷菜单.
            if ($message['request_type'] == $potato->shareRequestType) {
                // 分享了名片.
                $potato->potatoContactUid = $message['user_id'];
                $potato->potatoContactPhone = str_replace(array('+', ' '), '', $message['phone_number']);
                $potato->potatoContactFirstName = isset($message['first_name']) ? $message['first_name'] : "";
                $potato->potatoContactLastName = isset($message['last_name']) ? $message['last_name'] : "";
                $potato->potatoSendFirstName = isset($message['sender_first_name']) ? $message['sender_first_name'] : "";
                $potato->potatoSendLastName = isset($message['sender_last_name']) ? $message['sender_last_name'] : "";
                // 发送操作菜单.
                $result = $potato->callPotatoPerson();
                return $result;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 绑定telegram账号到系统.
     */
    public function actionBindPotato()
    {
        $isModify = false;
        $user = User::findOne(Yii::$app->user->id);
        if (!empty($user->potato_user_id) && !empty($user->potato_number)) {
            $isModify = true;
        }
        $model = new Potato();
        // 提交绑定数据.
        if ($model->load(Yii::$app->request->post())) {
            $updateRes = $model->bindPotatoData();
            if (!$updateRes) {
                return $this->render('bind-potato', ['model' => $model, 'isModify' => $isModify]);
            }

            Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
            return $this->redirect(['/home/user/app-bind']);
        } else {
            // 加载页面.
            return $this->render('bind-potato', ['model' => $model, 'isModify' => $isModify]);
        }
    }

    public function actionUnbundlePotato()
    {
        $model = new Potato();
        $updateRes = $model->unbundlePotatoData();
        if (!$updateRes) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/index','Operation failed'));
        } else {
            Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
        }

        return $this->redirect(['/home/user/app-bind']);
    }

}