<?php

return [
    'adminEmail' => 'admin@example.com',
    'user.passwordResetTokenExpire' => 1800, // 密码重置有效时间
    'fromMailAddress' => [
        'admin@example.com' => 'you name',
    ],
    'modules' => [
        /**
          'app-models-Article' => [
              'id' => 'articles', // 控制器名称（唯一）
              'label' => 'Articles', //  需要翻译的文本（app.php）
              'url' => ['/articles/index'], // 访问 URL
              'forceEmbed' => true, // 是否强制显示在控制面板中
          ],
         */
        'System Manage' => [
            'app-models-Tenant' => [
                'id' => 'tenants',
                'label' => 'Tenants',
                'url' => ['tenants/index'],
                'forceEmbed' => true,
            ],
            'app-models-User' => [
                'id' => 'users',
                'label' => 'Users',
                'url' => ['users/index'],
                'forceEmbed' => true,
            ],
        ],
        'Site Manage' => [
            'app-models-Lookup' => [
                'id' => 'lookups',
                'label' => 'Lookups',
                'url' => ['lookups/index'],
                'forceEmbed' => false,
            ],
            'app-models-Label' => [
                'id' => 'labels',
                'label' => 'Labels',
                'url' => ['labels/index'],
                'enabled' => true,
                'needAudit' => true,
            ],
            'app-models-FileUploadConfig' => [
                'id' => 'file-upload-config',
                'label' => 'File Upload Configs',
                'url' => ['file-upload-configs/index'],
                'forceEmbed' => false,
            ],
            'app-models-meta' => [
                'id' => 'meta',
                'label' => 'Meta',
                'url' => ['meta/index'],
                'forceEmbed' => false,
            ],
            'app-models-UserGroup' => [
                'id' => 'user-group',
                'label' => 'User Groups',
                'url' => ['user-groups/index'],
                'forceEmbed' => false,
            ],
            
        ],
        'Content Manage' => [
            'app-models-Article' => [
                'id' => 'articles',
                'label' => 'Articles',
                'url' => ['articles/index'],
                'enabled' => true,
            ],
            'app-models-Download' => [
                'id' => 'downloads',
                'label' => 'Downloads',
                'url' => ['downloads/index'],
                'enabled' => true,
                'needAudit' => true,
            ],
            'app-models-FriendlyLink' => [
                'id' => 'friendly-links',
                'label' => 'Friendly Links',
                'url' => ['friendly-links/index'],
                'forceEmbed' => false,
            ],
            'app-models-Slide' => [
                'id' => 'slides',
                'label' => 'Slides',
                'url' => ['slides/index'],
                'enabled' => true,
                'needAudit' => true,
            ],
            'app-models-AdSpace' => [
                'id' => 'ad-spaces',
                'label' => 'Ad Spaces',
                'url' => ['ad-spaces/index'],
                'forceEmbed' => false,
            ],
            'app-models-Ad' => [
                'id' => 'ads',
                'label' => 'Ads',
                'url' => ['ads/index'],
                'forceEmbed' => false,
            ],
        ]
    ],
];
