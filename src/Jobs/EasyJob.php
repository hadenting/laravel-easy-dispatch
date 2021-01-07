<?php

namespace Hadenting\EasyDispatch\Jobs;

use Hadenting\EasyDispatch\Interfaces\JobTaskInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EasyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 立即处理
     * @var bool
     */
    protected $dispatchNow;

    /**
     * 延迟时间
     * @var array|\Illuminate\Config\Repository|mixed
     */
    protected $delays = [];

    /**
     * 工作任务
     *
     * @var JobTaskInterface $jobTask
     */
    protected $jobTask;

    /**
     * 重试次数
     * @var int
     */
    protected $triesTimes;

    /**
     * 异常重试机制通过捕获异常来触发重试，所以如果调用的函数里已经捕获了异常，则不会重试
     * AutoJob constructor.
     * @param JobTaskInterface $jobTask
     * @param int $triesTimes
     */
    public function __construct(JobTaskInterface $jobTask, int $triesTimes = 0)
    {
        $this->jobTask = $jobTask;
        $this->triesTimes = $triesTimes;
        $this->dispatchNow = config('auto_move.dispatch_now', true);
        $this->delays = config('auto_move.delays', []);
    }

    /**
     * @throws \Throwable
     */
    public function handle()
    {
        try {
            $this->jobTask->handle();
        } catch (\Throwable $e) {
            $delayMinutes = $this->getNextDelayMinutes();
            if (!$this->dispatchNow && $delayMinutes) {
                static::dispatch($this->jobTask, ++$this->triesTimes)->onQueue($this->queue)->delay(now()->addMinutes($delayMinutes));
            } else {
                $this->jobTask->recodeError($e);
            }
        }
    }

    /**
     * @return mixed|null
     */
    protected function getNextDelayMinutes()
    {
        return $this->delays[$this->triesTimes] ?? null;
    }
}
