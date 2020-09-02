<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPodcast;
use App\Jobs\SendNotification;
use App\Mail\WelcomeMessage;
use App\Models\Podcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class PodcastController extends Controller
{
    public function store(Request $request)
    {
        // Create podcast...
        $podcast = new Podcast();

        ProcessPodcast::dispatch($podcast)
            ->delay(now()->addMinutes(10))//支持延迟调用 10分钟后queue才会processing
            // The Amazon SQS queue service has a maximum delay time of 15 minutes.
        ;

        $accountActive = '1';
        ProcessPodcast::dispatchIf($accountActive === true, $podcast);

        $accountSuspened = false;
        ProcessPodcast::dispatchUnless($accountSuspened === false, $podcast);


        SendNotification::dispatchAfterResponse();


        dispatch(function () {
            Mail::to('taylor@laravel.com')->send(new WelcomeMessage);
        })->afterResponse();

        //同步发送，立即处理 ，不会被加入到queue而是直接被当前进程内部给处理
        ProcessPodcast::dispatchNow($podcast);

        //一列跟追任务
        ProcessPodcast::withChain([
            new SendNotification,
            function () {
                Podcast::update([]);
            }
        ])->dispatch();


        ProcessPodcast::withChain([
            new SendNotification,

        ])->dispatch()->allOnConnection('redis')->allOnQueue('podcasts');

        ProcessPodcast::dispatch($podcast)->onQueue('processing');//是指定在一个connection里的queue执行

        ProcessPodcast::dispatch($podcast)->onConnection('sqs');
        ProcessPodcast::dispatch($podcast)->onQueue('processing')->onConnection('sqs');


        /** Queue Closure*/
        dispatch(function () use ($podcast) {
            $podcast->publish();
        });

    }
}
