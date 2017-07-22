<?php
namespace app\commands;

use udokmeci\yii2beanstalk\BeanstalkController;
use yii\helpers\Console;
use Yii;

class WorkerController extends BeanstalkController
{

    public function listenTubes(){
        return ["tube"];
    }

}