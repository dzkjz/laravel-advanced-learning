<?php

namespace App\Jobs;

use App\Jobs\Middleware\RateLimited;
use App\Models\Podcast;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class ProcessPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Podcast
     */
    private $podcast;

    /**
     * The number of times the job may be attempted. the maximum number of attempts 最大失败重试次数
     * @var int
     */
    public $tries = 5;

    /**
     * The maximum number of exceptions to allow before failing.超出3次抛异常，job就 failed
     * @var int
     */
    public $maxExceptions = 3;

    /**
     * job执行的最长时间
     * @var int
     */
    public $timeout = 120;

    /**
     * For convenience, you may choose to automatically delete jobs with missing models
     * by setting your job's deleteWhenMissingModels property to true:
     * @var bool
     */
    public $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected function __construct(Podcast $podcast)
    {
        //relationship也会跟着被serialized，这样序列化后的job字符串就会特别长，可以withoutRelations不序列化relationship
        $this->podcast = $podcast->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Redis::throttle('key')->block(0)->allow(1)->every(5)->then(function () {
            info('Lock obtained...');
            //Handle the job...
        }, function () {
            // Could not obtain lock...
            $this->release(5);
            return;
        });

        Redis::throttle(
            'key'//自定义，就是一个用于区分需要被频率限制的job类型的字符串，比如依照job的class name以及操作的Eloquent模型的ID来编写
        )->allow(10)->every(60)->then(function () {
            //Job logic...
        }, function () {
            //Could not obtain the lock...
            $this->release(10);//release方法执行，然后回到queue，也会将job已经attempts次数加1
            return;
        });
        //
        Redis::funnel('key')->limit(1)->then(function () {
            //Job logic...
        }, function () {
            //Could not obtain lock...

            $this->release(10);
            return;
        });
    }

    /**
     * Get the middleware the job should pass through.
     * @return array
     */

    public function middleware()
    {
        return [new RateLimited];
    }

    /**
     *
     * @return \Illuminate\Support\Carbon
     */
    public function retryUntil()
    {
        return now()->addSeconds(5);
    }

    /**
     * Handle a job failure.
     * @param \Throwable $exception
     */
    public function failed(\Throwable $exception)
    {
        //The failed method will not be called if the job was dispatched using the dispatchNow method.
    }

}
