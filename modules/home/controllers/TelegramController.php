<?php

namespace app\modules\home\controllers;

use app\modules\home\models\Telegram;
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
            $telegram = new Telegram();
            $message = isset($postData['message']) ? $postData['message'] : array();
            $telegram->telegramUid = isset($message['from']['id']) ? $message['from']['id'] : (isset($postData['callback_query']) ? $postData['callback_query']['from']['id'] : null);

            // 如果是用户第一次关注该机器人，发送欢迎信息,并发送内联快捷菜单.
            if ($message['text'] == '/start') {
                return $telegram->telegramWellcome();
            }

            if (!empty($message) && isset($message['contact'])) {
                // 分享了名片.
                $telegram->telegramContactUid = $message['contact']['user_id'];
                $telegram->telegramContactPhone = $message['contact']['phone_number'];
                $telegram->setInlineKeyboard();
                // 发送操作菜单.
                $telegram->sendData = [
                        'chat_id' => $telegram->telegramUid,
                        'text' => $telegram->telegramText,
                        'reply_markup' => [
                            'inline_keyboard' => $telegram->inlineKeyboard,
                        ]
                ];
                $result = $telegram->sendTelegramData();
                echo $result;
            } elseif (isset($postData['callback_query'])) {
                // 点击菜单回调操作.
                $telegram->callbackQuery = $postData['callback_query']['data'];
                $telegram->telegramContactFirstName = $postData['callback_query']['message']['chat']['first_name'];
                $telegram->telegramContactLastName = $postData['callback_query']['message']['chat']['last_name'];
                $action = explode('-', $telegram->callbackQuery);
                $action = $action[0];
                switch ($action) {
                    case $telegram->queryCallbackDataPre:
                        $result = $telegram->queryTelegramData();
                        echo $result;
                        break;
                    case $telegram->callCallbackDataPre;
                        $telegram->callTelegramPerson();
                        break;
                    case $telegram->bindCallbackDataPre:
                        $result = $telegram->sendBindCode();
                        return $result;
                        break;
                    default :
                        echo 'error_code :'.$telegram->errorCode['invalid_operation'];
                        break;
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}
