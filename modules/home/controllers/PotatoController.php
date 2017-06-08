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
            $message = isset($postData['result']) ? $postData['result'] : array();
            $potato->potatoUid = isset($message['sender_id']) ? $message['sender_id'] : $message['user_id'];

            // 如果是用户第一次关注该机器人，发送欢迎信息,并发送内联快捷菜单.
            if ($message['request_type'] == $potato->shareRequestType) {
                // 分享了名片.
                $potato->potatoContactUid = $message['user_id'];
                $potato->potatoContactPhone = str_replace(array('+', ' '), '', $message['phone_number']);
                $potato->potatoContactFirstName = $message['first_name'];
                // 发送操作菜单.
                $result = $potato->callPotatoPerson();
                return $result;
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}