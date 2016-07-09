<?php

namespace backend\controllers;

/**
 * 全局管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class GlobalController extends Controller
{

    public $layout = 'global';

    /**
     * 全局管理
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}
