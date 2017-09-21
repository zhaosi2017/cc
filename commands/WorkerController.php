<?php
/**
 * Created by PhpStorm.
 * User: zhangqing
 * Date: 2017/9/20
 * Time: 下午2:25
 */
namespace app\commands;

use udokmeci\yii2beanstalk\BeanstalkController;
use yii\helpers\Console;
use Yii;

class WorkerController extends BeanstalkController{

    public function listenTubes(){   //需要处理的结构类型
        return ["tube" ,'tract'];
    }

    public function actionTube($job){
        $data = $job->getData();
            
        self::DELETE;
        return true;
    }



}