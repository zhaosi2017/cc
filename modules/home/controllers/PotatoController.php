<?php

namespace app\modules\home\controllers;


use app\modules\home\models\Potato;
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
                        'actions' => ['index'],
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
            $message = isset($postData['message']) ? $postData['message'] : array();
            $potato->telegramUid = isset($message['from']['id']) ? $message['from']['id'] : (isset($postData['callback_query']) ? $postData['callback_query']['from']['id'] : null);

            // 如果是用户第一次关注该机器人，发送欢迎信息,并发送内联快捷菜单.
            if (isset($message['text']) && $message['text'] == $potato->getFirstText()) {
                return $potato->telegramWellcome();
            }

            if (!empty($message) && isset($message['contact'])) {
                // 分享了名片.
                $potato->telegramContactUid = $message['contact']['user_id'];
                $potato->telegramContactPhone = $message['contact']['phone_number'];
                // 发送操作菜单.
                return $potato->sendMenulist();
            } elseif (isset($postData['callback_query'])) {
                // 点击菜单回调操作.
                $potato->callbackQuery = $postData['callback_query']['data'];
                $potato->telegramContactFirstName = $postData['callback_query']['message']['chat']['first_name'];
                $potato->telegramContactLastName = $postData['callback_query']['message']['chat']['last_name'];
                $action = explode('-', $potato->callbackQuery);
                $action = $action[0];
                switch ($action) {
                    case $potato->queryCallbackDataPre:
                        $result = $potato->queryTelegramData();
                        echo $result;
                        break;
                    case $potato->callCallbackDataPre;
                        $potato->callTelegramPerson();
                        break;
                    case $potato->bindCallbackDataPre:
                        $result = $potato->sendBindCode();
                        return $result;
                        break;
                    default :
                        echo 'error_code :'.$potato->errorCode['invalid_operation'];
                        break;
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}