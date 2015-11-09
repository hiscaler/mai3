<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\Url;

/**
 * 记录操作按钮部件
 */
class MenuButtons extends Widget
{

    public $items = [];

    public function run()
    {
        $output = '<div class="btn-group">';
        foreach ($this->items as $i => $item) {
            if (isset($item['visible']) && $item['visible'] === false) {
                unset($this->items[$i]);
                continue;
            }
            $output .= '<a class="btn btn-xs btn-white" href="' . Url::toRoute($item['url']) . '">' . $item['label'] . '</a>';
        }


        return $output . '</div>';
    }

}
