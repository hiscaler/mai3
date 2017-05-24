<?php

return [
    'adminEmail' => 'admin@example.com',
    'user.passwordResetTokenExpire' => 1800, // 密码重置有效时间
    'fromMailAddress' => [
        'admin@example.com' => 'you name',
    ],
    'api' => [
        'dbCacheTimes' => 360, // 数据库缓存时间（秒）0 表示永久缓存，null 表示禁止缓存
        'assetResourceUrlPrefix' => '', // 静态资源 URL（比如：http://www.example.com）
        'assetResourceUrlPrefixReplacePairs' => [
            '/uploads/' => '{assetResourceUrlPrefix}/uploads/'
        ]
    ]
];
