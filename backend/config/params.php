<?php

Yii::$container->set('yii\grid\GridView', [
    'tableOptions' => [
        'class' => 'table table-striped dataTable',
    ],
    'layout' => '<div class="row"><div class="col-md-12">{items}</div></div><div class="row"><div class="col-sm-5"><div class="dataTables_info" role="status" aria-live="polite">{summary}</div></div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
]);

return [
    'adminEmail' => 'admin@example.com',
    'pay' => [
        // 支付宝支付
        'alipay' => [
            [
                'name' => 'alipay_account',
                'label' => '支付宝帐户',
                'description' => '',
                'type' => 'text',
                'value' => ''
            ],
            [
                'name' => 'alipay_key',
                'label' => '交易安全校验码',
                'description' => '',
                'type' => 'text',
                'value' => ''
            ],
            [
                'name' => 'alipay_partner',
                'label' => '合作者身份ID',
                'description' => '',
                'type' => 'text',
                'value' => ''
            ],
            [
                'name' => 'alipay_pay_method',
                'label' => '接口类型',
                'description' => '',
                'type' => 'radio',
                'value' => '',
                'items' => [
                    '使用标准接口',
                    '使用担保交易接口',
                    '使用即时到帐交易接口',
                ]
            ]
        ],
        // 微信支付
        'wxpay' => [
            [
                'name' => 'appid',
                'label' => 'App ID',
                'description' => '',
                'type' => 'text',
                'value' => '',
            ],
            [
                'name' => 'appsecret',
                'label' => 'App Secret',
                'description' => '',
                'type' => 'text',
                'value' => '',
            ],
            [
                'name' => 'mchid',
                'label' => '商户id',
                'description' => '',
                'type' => 'text',
                'value' => '',
            ],
            [
                'name' => 'key',
                'label' => '商户支付密钥',
                'description' => '',
                'type' => 'text',
                'value' => '',
            ]
        ]
    ]
];
