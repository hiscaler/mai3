<?php

return [
    'class' => 'yii\rest\UrlRule',
    // Brand
    'GET,HEAD api/brands' => 'api/brand/index',
    'GET,HEAD api/brands/list' => 'api/brand/list',
    'GET,HEAD api/brands/<id:\d+>' => 'api/brand/view',
];
