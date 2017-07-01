<?php
/**
 * Created by PhpStorm.
 * User: nengliu
 * Date: 2017/6/29
 * Time: 下午3:20
 */

namespace app\modules\home\controllers;

use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
    public static function handleMissingTranslation(MissingTranslationEvent $event)
    {
        $event->translatedMessage = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @";
    }
}