<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/application.css',
        'css/common.css',
        'css/widget-grid-view.css',
        'css/form.css',
        'layer/layer.css',
    ];
    public $js = [
        'js/doT.min.js',
        'layer/layer.js',
        'js/application.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];

}
