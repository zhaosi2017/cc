<?php

namespace app\modules\home\controllers;

use app\modules\home\models\Potato;
use app\modules\home\models\PotatoMap;
use app\modules\home\models\PotatoMapServer;
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
                        'actions' => ['index', 'bind-potato', 'unbundle-potato','potato-map'],
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
            file_put_contents('/tmp/potato.txt', var_export($postData, true).PHP_EOL, 8);
            $potato = new Potato();
            $message = isset($postData['result']) ? $postData['result'] : array();
            $potato->potatoUid = isset($message['sender_id']) ? $message['sender_id'] : $message['user_id'];
            $inlineMessageId = isset($message['inline_message_id']) ? $message['inline_message_id'] : '';
            $userId = isset($message['user_id']) ? $message['user_id'] : '';
            $potatoMapServer = new PotatoMapServer();
            // 如果是用户第一次关注该机器人，发送欢迎信息,并发送内联快捷菜单.
            if (isset($message['text']) && $message['text'] == $potato->getFirstText()) {
                return $potato->potatoWellcome();
            }

            if ($message['request_type'] == $potato->shareRequestType) {
                // 分享了名片.
                $potato->potatoContactUid = $message['user_id'];
                $potato->potatoContactPhone = str_replace(array('+', ' '), '', $message['phone_number']);
                $potato->potatoContactFirstName = isset($message['first_name']) ? $message['first_name'] : "";
                $potato->potatoContactLastName = isset($message['last_name']) ? $message['last_name'] : "";
                $potato->potatoSendFirstName = isset($message['sender_first_name']) ? $message['sender_first_name'] : "";
                $potato->potatoSendLastName = isset($message['sender_last_name']) ? $message['sender_last_name'] : "";
                // 发送操作菜单.
                $result = $potato->sendMenulist();
                return $result;
            } else if ($message['request_type'] == $potato->callBackRequestType) {
                // 点击按钮会调.
                $result = $potato->checkRate();
                if ($result) {
                    return $potato->errorCode['error'];
                }

                $callbackData = explode('-', $message['data']);
                $potato->potatoContactUid = $callbackData[1];
                $action = $callbackData[0];
                switch ($action) {
                        // 正常呼叫.
                    case $potato->callCallbackDataPre:
                        $potato->potatoSendFirstName = $callbackData[2];
                        $potato->potatoContactFirstName = $callbackData[3];
                        $result = $potato->callPotatoPerson();
                        return $result;
                        break;
                        // 呼叫紧急联系人.
                    case $potato->callUrgentCallbackDataPre:
                        $calledId = $potato->callbackQuery[2];
                        $potato->potatoSendFirstName = $potato->callbackQuery[3];
                        $potato->potatoContactFirstName = $callbackData[4];
                        $result = $potato->callPotatoPerson($calledId);
                        return $result;
                        break;
                        // 加白名单.
                    case $potato->whiteCallbackDataPre:
                        $result = $potato->joinWhiteList();
                        return $result;
                        break;
                        // 从白名单中剔除.
                    case $potato->unwhiteCallbackDataPre:
                        $result = $potato->unbindWhiteList();
                        return $result;
                        break;
                        // 开起白名单功能.
                    case $potato->whitelistSwitchCallbackDataPre:
                        $result = $potato->enableWhiteSwith();
                        return $result;
                        break;
                        // 关闭白名单功能.
                    case $potato->unwhitelistSwitchCallbackDataPre:
                        $result = $potato->disableWhiteSwith();
                        return $result;
                        break;
                        // 加入黑名单.
                    case $potato->blackCallbackDataPre:
                        $result = $potato->joinBlackList();
                        return $result;
                        // 从黑名单中剔除.
                    case $potato->unblackCallbackDataPre:
                        $result = $potato->unbindBlackList();
                        return $result;
                    default :
                        echo 'error_code :'.$potato->errorCode['invalid_operation'];
                        break;
                }
            }else if($message['request_type'] == $potatoMapServer->requestTextType){
                    $potatoMapServer->potatoUid = $potato->potatoUid;
                    $replyMessageId = isset($message['reply_to_message'])? $message['reply_to_message']: '';
                    $potatoMapServer->key = $potato->potatoUid.'-'.$replyMessageId.'-potatoMap';
                    $potatoMapServer->searchMapText = json_encode($message);
                    return $potatoMapServer->sendVenue();
            }else if($message['request_type'] == $potatoMapServer->requestLocationType || $message['request_type'] == $potatoMapServer->requestMapType){
                $potatoMapServer->potatoUid = $potato->potatoUid;
                $potatoMapServer->key = $potato->potatoUid.'-'.$message['message_id'].'-potatoMap';
                $potatoMapServer->searchMapText = json_encode($message);
                return $potatoMapServer->addMap();
            }

            if (!empty($inlineMessageId) && !empty($userId)) {
                return $potato->sendCallbackAnswer($userId, $inlineMessageId);
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

    public function actionPotatoMap(){
        $potatoMapServer = new PotatoMapServer();
        $potatoMapServer->potatoUid = 8009109;
        $potatoMapServer->searchMapText = json_encode(['text'=>'/map 大学']);
        return $potatoMapServer->sendVenue();
    }


}