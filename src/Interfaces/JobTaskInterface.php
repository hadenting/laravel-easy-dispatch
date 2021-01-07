<?php

namespace Hadenting\EasyDispatch\Interfaces;

/**
 * 工作任务 放到Job里的任务
 * Interface PacketInterface
 * @package App\AutoJob\Interfaces
 */
interface JobTaskInterface
{
    public function handle();

    public function recodeError(\Throwable $e = null);
}