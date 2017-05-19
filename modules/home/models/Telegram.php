<?php
namespace app\modules\home\models;

use yii;
use yii\base\Model;
use app\modules\home\models\User;

class Telegram extends Model
{

    private $queryText = "查询";
    private $callText = "呼叫";
    private $bindText = '分享自己名片';
    private $webhook;
    private $code;
    private $telegram_uid;
    private $telegram_contact_uid;
    private $telegram_contact_phone;
    private $query_callback_data_pre = 'cc_query';
    private $call_callback_data_pre = 'cc_call';
    private $bind_callback_data_pre = 'cc_bind';
    private $query_callback_data;
    private $call_callback_data;
    private $bind_callback_data;

    private $query_menu;
    private $call_menu;
    private $bind_menu;
    private $inline_keyboard = [];
    private $send_data;
    private $error_code = [
        'invalid_operation' => 401,
        'not_yourself' => 402,
        'exist' => 403,
        'noexist' => 404,
    ];
    private $error_message = [
        'invalid_operation' => '无效的操作',
        'not_yourself' => '不是自己的名片',
        'exist' => '已经绑定过',
        'noexist' => '没有查询到此人',
    ];

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [];
    }

    /**
     * 设置webhook
     */
    public function setWebhook()
    {
        $this->webhook = 'https://api.telegram.org/bot335051539:AAFEH5fCTxtizN3gxv1-gXAGRnXkzkeXieM/sendMessage';
    }

    /**
     * 设置验证码.
     */
    public function setCode()
    {
        // 查询是否绑定自己的账号.
        if ($this->telegram_uid != $this->telegram_contact_uid) {
            return 'error_code :'.$this->error_code['not_yourself'];
        }

        // 查询是否绑定.
        $res = User::findOne(['telegram_uid' => $this->telegram_uid]);
        if ($res) {
            return 'error_code :'.$this->error_code['exist'];
        } else {
            $dealData = [
                Yii::$app->params['telegram_pre'],
                $this->telegram_contact_uid,
                $this->telegram_contact_phone,
            ];

            $dealData = implode('-', $dealData);
            $this->code = base64_encode(Yii::$app->security->encryptByKey($dealData, Yii::$app->params['telegram']));
        }
    }

    /**
     * 设置查询菜单.
     */
    public function setQueryMenu()
    {
        $this->query_menu = array(
            'text' => $this->queryText,
            'callback_data' => $this->query_callback_data,
        );
    }

    /**
     * 设置呼叫菜单.
     */
    public function setCallMenu()
    {
        $this->call_menu = array(
            'text' => $this->callText,
            'callback_data' => $this->call_callback_data,
        );
    }

    /**
     * 设置绑定菜单.
     */
    public function setBindMenu()
    {
        $this->bind_menu = array(
            'text' => $this->bindText,
            'callback_data' => $this->bind_callback_data,
        );
    }

    /**
     * 设置查询回调参数.
     */
    public function setQueryCallbackData()
    {
        $this->query_callback_data = implode('-', array($this->query_callback_data_pre, $this->telegram_contact_uid, $this->telegram_contact_phone));
    }

    /**
     * 设置呼叫回调参数.
     */
    public function setCallCallbackData()
    {
        $this->call_callback_data = implode('-', array($this->call_callback_data_pre, $this->telegram_contact_uid, $this->telegram_contact_phone));
    }

    /**
     * 设置绑定回调参数.
     */
    public function setBindCallbackData()
    {
        $this->call_callback_data = implode('-', array($this->bind_callback_data_pre, $this->telegram_contact_uid, $this->telegram_contact_phone));
    }

    /**
     * 设置菜单.
     *
     * @return json.
     */
    public function setInlineKeyboard()
    {
        // 查询是否绑定.
        $res = User::findOne(['telegram_uid' => $this->telegram_uid]);
        if ($res) {
            $this->inline_keyboard[] = [
                [
                    $this->query_menu,
                    $this->call_menu,
                ]
            ];
        } else {
            $this->inline_keyboard[] = [
                $this->bind_menu,
                [
                    $this->query_menu,
                    $this->call_menu,
                ]
            ];
        }
    }

    /**
     * 获取webhook.
     */
    public function getWebhook()
    {
        return $this->webhook;
    }

    /**
     * 获取code.
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * 获取查询菜单.
     */
    public function getQueryMenu()
    {
        return $this->query_menu;
    }

    /**
     * 获取呼叫菜单.
     */
    public function getCallMenu()
    {
        return $this->call_menu;
    }

    /**
     * 获取绑定菜单.
     */
    public function getBindMenu()
    {
        return $this->bind_menu;
    }

    /**
     * 获取查询回调参数.
     */
    public function getQueryCallbackData()
    {
        return $this->query_callback_data;
    }

    /**
     * 获取呼叫回调参数
     */
    public function getCallCallbackData()
    {
        return $this->call_callback_data;
    }

    /**
     * 获取绑定回调参数.
     */
    public function getBindCallbackData()
    {
        return $this->bind_callback_data;
    }

    /**
     * 获取错误码.
     */
    public function getErrorCode()
    {
        return $this->error_code;
    }

    /**
     * 获取错误消息.
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * 设置菜单.
     *
     * @return json.
     */
    public function getInlineKeyboard()
    {
        return $this->inline_keyboard;
    }

    /**
     * 查询telegram账号.
     */
    public function queryTelegramData()
    {
        // 查询是否绑定.
        $res = User::findOne(['telegram_uid' => $this->telegram_uid]);
        return $res ? $this->error_message['exist'] : $this->error_message['noexist'];
    }

    /**
     * 呼叫telegram账号.
     */
    public function callTelegramPerson()
    {

    }

    /**
     * 发送菜单.
     *
     * @return json.
     */
    public function sendData()
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $this->webhook,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $this->send_data,
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "postman-token: 66e489ad-7652-33ab-41dd-42c4e347d0b8"
                ),
            )
        );

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "error #:" . $err;
        } else {
            return $response;
        }

    }

}