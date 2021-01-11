<?php


namespace EasyDispatch;


use EasyDispatch\Interfaces\JobTaskInterface;
use EasyDispatch\Jobs\EasyJob;

class EasyDispatch
{
    /**
     * 队列名称
     * @var string
     */
    protected $queueName;
    /**
     * 是否启用队列
     * @var  $dispatchNow bool
     */
    protected $dispatchNow;

    public function __construct()
    {
        $this->queueName = config('easy_dispatch.queue_name');
        $this->dispatchNow = config('easy_dispatch.dispatch_now');
    }

    /**
     * 派遣工作
     * @param JobTaskInterface $task
     */
    public function dispatchTask(JobTaskInterface $task)
    {
        if ($this->isDispatchNow()) {
            EasyJob::dispatchNow($task);
        } else {
            EasyJob::dispatch($task)->onQueue($this->getQueueName());
        }
    }

    /**
     * @return bool
     */
    protected function isDispatchNow(): bool
    {
        return $this->dispatchNow;
    }

    /**
     * @return \Illuminate\Config\Repository|mixed|string
     */
    protected function getQueueName()
    {
        return $this->queueName;
    }
}