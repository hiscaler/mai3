<?php

namespace common\models;

/**
 * 常量定义
 *
 * @author hiscaler
 */
class Constant
{

    /**
     * 布尔值定义
     */
    /** 假 */    
    const BOOLEAN_FALSE = 0;
    /** 真 */
    const BOOLEAN_TRUE = 1;

    /**
     * 状态值定义
     */
    /** 待审核 */
    const STATUS_PENDING = 0;
    /** 激活 */
    const STATUS_ACTIVE = 1;

    /**
     * 分类类型值定义
     */
    /** 文章分类 */
    const CATEGORY_ARTICLE = 0;
    /** 资讯分类 */
    const CATEGORY_NEWS = 1;
    /** 商品分类 */
    const CATEGORY_ITEM = 2;

}
