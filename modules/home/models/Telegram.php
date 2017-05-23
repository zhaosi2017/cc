<?php
namespace app\modules\home\models;

use yii;
use yii\base\Model;
use app\modules\home\models\User;

class Telegram extends Model
{

    private $queryText = "查询";
    private $telegramText = '操作菜单';
    private $callText = "呼叫";
    private $bindText = '绑定账号';
    private $webhook;
    private $code;
    private $telegramUid;
    private $telegramContactUid;
    private $telegramContactPhone;
    private $queryCallbackDataPre = 'cc_query';
    private $callCallbackDataPre = 'cc_call';
    private $bindCallbackDataPre = 'cc_bind';
    private $queryCallbackData;
    private $callCallbackData;
    private $bindCallbackData;

    private $queryMenu;
    private $callMenu;
    private $bindMenu;
    private $inlineKeyboard;
    private $sendData;
    private $errorCode = [
        'invalid_operation' => 401,
        'not_yourself' => 402,
        'exist' => 403,
        'noexist' => 404,
    ];
    private $errorMessage = [
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
     * @param $value
     */
    public function setTelegramUid($value)
    {
        $this->telegramUid = $value;
    }

    /**
     * @param $value
     */
    public function setTelegramContactUid($value)
    {
        $this->telegramContactUid = $value;
    }

    /**
     * @param $value
     */
    public function setTelegramContactPhone($value)
    {
        $this->telegramContactPhone = $value;
    }

    /**
     * 设置验证码.
     */
    public function setCode()
    {
        // 查询是否绑定自己的账号.
        if ($this->telegramUid != $this->telegramContactUid) {
            return 'error_code :'.$this->errorCode['not_yourself'];
        }

        // 查询是否绑定.
        $res = User::findOne(['telegram_uid' => $this->telegramUid]);
        if ($res) {
            return 'error_code :'.$this->errorCode['exist'];
        } else {
            $dealData = [
                Yii::$app->params['telegram_pre'],
                $this->telegramContactUid,
                $this->telegramContactPhone,
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
        $this->setQueryCallbackData();
        $this->queryMenu = array(
            'text' => $this->queryText,
            'callback_data' => $this->queryCallbackData,
        );
    }

    /**
     * 设置呼叫菜单.
     */
    public function setCallMenu()
    {
        $this->setCallCallbackData();
        $this->callMenu = array(
            'text' => $this->callText,
            'callback_data' => $this->callCallbackData,
        );
    }

    /**
     * 设置绑定菜单.
     */
    public function setBindMenu()
    {
        $this->setBindCallbackData();
        $this->bindMenu = array(
            'text' => $this->bindText,
            'callback_data' => $this->bindCallbackData,
        );
    }

    /**
     * 设置查询回调参数.
     */
    public function setQueryCallbackData()
    {
        $this->queryCallbackData = implode('-', array($this->queryCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone));
    }

    /**
     * 设置呼叫回调参数.
     */
    public function setCallCallbackData()
    {
        $this->callCallbackData = implode('-', array($this->callCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone));
    }

    /**
     * 设置绑定回调参数.
     */
    public function setBindCallbackData()
    {
        $this->bindCallbackData = implode('-', array($this->bindCallbackDataPre, $this->telegramContactUid, $this->telegramContactPhone));
    }

    /**
     * 设置菜单.
     *
     * @return json.
     */
    public function setInlineKeyboard()
    {
        // 查询是否绑定.
        $res = User::findOne(['telegram_user_id' => $this->telegramUid]);
        if ($res) {
            $this->setQueryMenu();
            $this->setCallMenu();
            $this->inlineKeyboard = [
                [
                    $this->queryMenu,
                    $this->callMenu,
                ]
            ];
        } else {
            $this->setBindMenu();
            $this->setQueryMenu();
            $this->setCallMenu();
            if ($this->telegramContactUid == $this->telegramUid) {
                $this->inlineKeyboard = [
                    [
                        $this->bindMenu,
                    ],
                    [
                        $this->queryMenu,
                        $this->callMenu,
                    ]
                ];
            } else {
                $this->inlineKeyboard = [
                    [
                        $this->queryMenu,
                        $this->callMenu,
                    ]
                ];
            }
        }
    }

    /**
     * @param $value
     */
    public function setSendData($value)
    {
        $this->sendData = $value;
    }

    /**
     * @return mixeds
     */
    public function getTelegramText()
    {
        return $this->telegramText;
    }

    /**
     * @return string
     */
    public function getQueryText()
    {
        return $this->queryText;
    }

    /**
     * @return string
     */
    public function getCallText()
    {
        return $this->callText;
    }

    /**
     * @return string
     */
    public function getBindText()
    {
        return $this->bindText;
    }

    /**
     * @return mixed
     */
    public function getTelegramUid()
    {
        return $this->telegramUid;
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
        return $this->queryMenu;
    }

    /**
     * 获取呼叫菜单.
     */
    public function getCallMenu()
    {
        return $this->callMenu;
    }

    /**
     * 获取绑定菜单.
     */
    public function getBindMenu()
    {
        return $this->bindMenu;
    }

    /**
     * 获取查询回调参数.
     */
    public function getQueryCallbackData()
    {
        return $this->queryCallbackData;
    }

    /**
     * 获取呼叫回调参数
     */
    public function getCallCallbackData()
    {
        return $this->callCallbackData;
    }

    /**
     * 获取绑定回调参数.
     */
    public function getBindCallbackData()
    {
        return $this->bindCallbackData;
    }

    /**
     * @return string
     */
    public function getQueryCallbackDataPre()
    {
        return $this->queryCallbackDataPre;
    }

    /**
     * @return string
     */
    public function getCallCallbackDataPre()
    {
        return $this->callCallbackDataPre;
    }

    /**
     * @return string
     */
    public function getBindCallbackDataPre()
    {
        return $this->bindCallbackDataPre;
    }

    /**
     * @return mixed
     */
    public function getSendData()
    {
        return $this->sendData;
    }

    /**
     * 获取错误码.
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * 获取错误消息.
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * 设置菜单.
     *
     * @return json.
     */
    public function getInlineKeyboard()
    {
        return $this->inlineKeyboard;
    }

    /**
     * 查询telegram账号.
     */
    public function queryTelegramData()
    {
        // 查询是否绑定.
        $res = User::findOne(['telegram_user_id' => $this->telegramUid]);
        return $res ? $this->errorMessage['exist'] : $this->errorMessage['noexist'];
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
    public function sendTelegramData()
    {
        $this->setWebhook();
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
                CURLOPT_POSTFIELDS => $this->sendData,
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