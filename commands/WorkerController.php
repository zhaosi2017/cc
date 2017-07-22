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

    public function actionTube($job){
        $sentData = $job->getData();
        try {
            // something useful here
            if($everthingIsAllRight == true){
                fwrite(STDOUT, Console::ansiFormat("- Everything is allright"."\n", [Console::FG_GREEN]));
                return self::DELETE; //Deletes the job from beanstalkd
            }
            fwrite(STDOUT, Console::ansiFormat("- Not everything is allright!!!"."\n", [Console::FG_GREEN]));
            return self::DELAY; //Delays the job for later try
            // if you return anything else job is released.
        } catch (\Exception $e) {
            //If there is anything to do.
            fwrite(STDERR, Console::ansiFormat($e."\n", [Console::FG_RED]));
            return self::DELETE;
        }
    }
}