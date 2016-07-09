<?php

namespace backend\controllers;

/**
 * 店铺管理
 * 
 * @author hiscaler <hiscaler@gmail.com>
 */
class ShopController extends Controller
{

    public $layout = 'shop';

    /**
     * 店铺管理
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}
