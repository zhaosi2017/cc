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

            if (isset($postData['contact'])) {
                // 分享了名片.
                $telegram->telegram_contact_uid = $postData['contact']['user_id'];
                $telegram->telegram_contact_phone = $postData['contact']['phone_number'];
                $telegram->telegram_uid = $postData['from']['id'];
                // 发送操作菜单.
                $telegram ->send_data = [
                        'chat_id' => $telegram->telegram_from_id,
                        'text' => $telegram->telegram_text,
                        'reply_markup' => [
                            'inline_keyboard' => $telegram->setInlineKeyboard(),
                        ]
                ];
                $telegram->send_data = json_encode($telegram->send_data, true);
                $telegram->sendData();
            } elseif (isset($postData['callback_query'])) {
                // 点击菜单回调操作.
                $action = $postData['callback_query']['data'];
                $action = explode('-', $action);
                $action = $action[0];
                switch ($action) {
                    case $telegram->query_callback_data_pre:
                        echo $telegram->queryTelegramData();
                        break;
                    case $telegram->call_callback_data_pre;
                        $telegram->callTelegramPerson();
                        break;
                    case $telegram->bind_callback_data_pre:
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
