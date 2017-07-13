<?php

namespace app\modules\home\controllers;
/**
 * Created by PhpStorm.
 * User: nengliu
 * Date: 2017/7/13
 * Time: 上午10:41
 */


class NexmoController extends GController
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
                        'actions' => ['index',],
                        'roles' => ['@'],
                    ],
                ],
                /*
                'denyCallback' => function($rule, $action) {
                    echo 'You are not allowed to access this page!';
                }
                */
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
     * 呼叫.
     */
    public function index()
    {

    }
}