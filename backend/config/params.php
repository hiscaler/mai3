<?php

Yii::$container->set('yii\grid\GridView', [
    'tableOptions' => [
        'class' => 'table table-striped dataTable',
    ],
    'layout' => '<div class="row"><div class="col-md-12">{items}</div></div><div class="row"><div class="col-sm-5"><div class="dataTables_info" role="status" aria-live="polite">{summary}</div></div><div class="col-sm-7"><div class="dataTables_paginate paging_simple_numbers">{pager}</div></div></div>',
]);

return [
    'adminEmail' => 'admin@example.com',
];
