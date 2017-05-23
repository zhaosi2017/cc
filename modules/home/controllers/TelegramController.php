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

            if (!empty($message) && isset($message['contact'])) {
                // 分享了名片.
                $telegram->telegramContactUid = $message['contact']['user_id'];
                $telegram->telegramContactPhone = $message['contact']['phone_number'];
                $telegram->telegramUid = $message['from']['id'];
                $telegram->setInlineKeyboard();
                // 发送操作菜单.
                $telegram->sendData = [
                        'chat_id' => $telegram->telegramUid,
                        'text' => $telegram->telegramText,
                        'reply_markup' => [
                            'inline_keyboard' => $telegram->inlineKeyboard,
                        ]
                ];
                $telegram->sendData = json_encode($telegram->sendData, true);
                $result = $telegram->sendTelegramData();
                echo $result;
            } elseif (isset($postData['callback_query'])) {
                // 点击菜单回调操作.
                $action = $postData['callback_query']['data'];
                $action = explode('-', $action);
                $action = $action[0];
                switch ($action) {
                    case $telegram->queryCallbackDataPre:
                        echo $telegram->queryTelegramData();
                        break;
                    case $telegram->callCallbackDataPre;
                        $telegram->callTelegramPerson();
                        break;
                    case $telegram->bindCallbackDataPre:
                        echo $telegram->getCode();
                        break;
                    default :
                        echo 'error_code :'.$telegram->error_code['invalid_operation'];
                        break;
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}
