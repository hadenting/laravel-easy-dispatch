<?php

/**
 * 自动推送设置
 */
return [
    /**
     * 是否立即推送  默认：立即推送，默认不使用队列
     */
    'dispatch_now' => env('DISPATCH_NOW', true),

    /**
     * 队列名称
     */
    'queue_name' => env('QUEUE_NAME', 'easy_dispatch'),

    /**
     * Job异常重试延迟时间 单位：分钟（只有队列模式可以生效）
     */
    'delays' => [
//            1
//            5, 15, 30
    ],
];