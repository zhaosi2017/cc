<?php

namespace app\modules\home\controllers;

use app\modules\home\models\CallRecord;
use app\modules\home\models\Telegram;
use app\modules\home\models\TelegramMaps;
use app\modules\home\models\User;
use app\modules\home\models\UserBindApp;
use Yii;
use app\controllers\GController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class TelegramController extends GController
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
                        'actions' => ['index', 'bind-telegram', 'unbundle-telegram'],
                        'roles' => ['@'],
                    ],
                ],
                /*
                'denyCallback' => function($rule, $action) {
                    echo 'You are not allowed to access this page!';
                }
                */
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
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        try{
            // $postData = Yii::$app->request->bodyParams;
            $postData = @file_get_contents('php://input');
            $postData = json_decode($postData, true);
            file_put_contents('/tmp/telegram.log',var_export($postData,true).PHP_EOL,8);
            $telegram = new Telegram();
            $message = isset($postData['message']) ? $postData['message'] : array();
            $telegram->telegramUid = isset($message['from']['id']) ? $message['from']['id'] : (isset($postData['callback_query']) ? $postData['callback_query']['from']['id'] : null);
            if (isset($message['from']['language_code'])) {
                $tmp = explode('-', $message['from']['language_code']);
                $telegram->language = array_shift($tmp);
            }

            // 如果是用户第一次关注该机器人，发送欢迎信息,并发送内联快捷菜单.
            if (isset($message['text']) && $message['text'] == $telegram->getFirstText()) {
                return $telegram->telegramWellcome();
            }

            if (!empty($message) && isset($message['contact'])) {
                // 分享了名片.
                $telegram->telegramContactUid = $message['contact']['user_id'];
                $telegram->telegramContactPhone = $message['contact']['phone_number'];
                $telegram->telegramContactFirstName = isset($message['contact']['first_name']) ? $message['contact']['first_name'] : "";
                $telegram->telegramContactLastName = isset($message['contact']['last_name']) ? $message['contact']['last_name'] : '';
                $telegram->telegramFirstName = isset($message['from']['first_name']) ? $message['from']['first_name'] : "";
                $telegram->telegramLastName = isset($message['from']['last_name']) ? $message['from']['last_name'] : '';

                // 发送操作菜单.
                return $telegram->sendMenulist();
            } elseif (isset($postData['callback_query'])) {
                // 点击菜单回调操作.
//                $result = $telegram->checkRate();
//                if ($result) {
//                    return $telegram->errorCode['error'];
//                }

                if (isset($postData['callback_query']['message']['chat']['first_name'])) {
                    $telegram->telegramFirstName = $postData['callback_query']['message']['chat']['first_name'];
                }
                if (isset($postData['callback_query']['message']['chat']['last_name'])) {
                    $telegram->telegramLastName = $postData['callback_query']['message']['chat']['last_name'];
                }

                $callbackData = explode('-', $postData['callback_query']['data']);
                $telegram->callbackQuery = $callbackData;
                $telegram->telegramContactUid = $callbackData[1];
                $action = $callbackData[0];
                switch ($action) {
                    case $telegram->callCallbackDataPre:
                        $telegram->telegramFirstName = $callbackData[2];
                        $telegram->telegramContactFirstName = $callbackData[3];
                        $message['link'] = false;
                        $result = $telegram->call(CallRecord::Record_Type_none , $message);
                        return $result;
                        break;
                    case $telegram->callUrgentCallbackDataPre:
                        $calledId = $callbackData[2];
                        $telegram->telegramFirstName = $callbackData[3];
                        $telegram->telegramContactFirstName = $callbackData[4];
                        if(isset($callbackData[5]) && !empty($callbackData[5])) {
                            $message['link'] = true;
                        }else{
                            $message['link'] = false;
                        }
                        $result = $telegram->call(CallRecord::Record_Type_emergency,$message);
                        return $result;
                        break;
                    case $telegram->whiteCallbackDataPre:
                        $result = $telegram->joinWhiteList();
                        return $result;
                        break;
                    case $telegram->unwhiteCallbackDataPre:
                        $result = $telegram->unbindWhiteList();
                        return $result;
                        break;
                    case $telegram->whitelistSwitchCallbackDataPre:
                        $result = $telegram->enableWhiteSwith();
                        return $result;
                        break;
                    case $telegram->unwhitelistSwitchCallbackDataPre:
                        $result = $telegram->disableWhiteSwith();
                        return $result;
                        break;
                    case $telegram->blackCallbackDataPre:
                        $result = $telegram->joinBlackList();
                        return $result;
                    case $telegram->unblackCallbackDataPre:
                        $result = $telegram->unbindBlackList();
                        return $result;
                    default :
                        echo 'error_code :'.$telegram->errorCode['invalid_operation'];
                        break;
                }
            }elseif(!empty($message)){    //demo  测试地图功能用

                $maps = new TelegramMaps();
                $maps->telegramUid = $message['chat']['id'];
                return $maps->mapOrders($message);

            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 绑定telegram账号到系统.
     */
    public function actionBindTelegram($id = 0)
    {
        $isModify = false;
        $user = UserBindApp::findOne(['user_id'=>Yii::$app->user->id,'id'=>$id]);
        if (!empty($user)) {
            $isModify = true;
        }
        $model = new Telegram();
        // 提交绑定数据.
        if ($model->load(Yii::$app->request->post())) {
            $id = isset($_POST['id']) && (int)$_POST['id'] ? (int)$_POST['id']:0;
            $updateRes = $model->bindTelegramData($id);
            if (!$updateRes) {
                return $this->render('bind-telegram', ['model' => $model, 'isModify' => $isModify]);
            }

            Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
            return $this->redirect(['/home/user/app-bind']);
        } else {
            // 加载页面.
            return $this->render('bind-telegram', ['model' => $model, 'isModify' => $isModify]);
        }
    }

    public function actionUnbundleTelegram($id=0)
    {
        $model = new Telegram();
        $updateRes = $model->unbundleTelegramData($id);
        if (!$updateRes) {
            Yii::$app->getSession()->setFlash('error', Yii::t('app/index','Operation failed'));
        } else {
            Yii::$app->getSession()->setFlash('success', Yii::t('app/index','Successful operation'));
        }

        return $this->redirect(['/home/user/app-bind']);
    }

}
