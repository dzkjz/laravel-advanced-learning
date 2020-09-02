<?php

namespace App\Console;

use App\Console\Commands\EmailsCommand;
use App\Console\Commands\SendEmails;
use App\Jobs\Heartbeat;
use App\ScheduleObject\DeleteRecentUsers;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // You may also manually register commands by adding its class name to the $commands property
        // of your app/Console/Kernel.php file.
        // When Artisan boots, all the commands listed in this property
        // will be resolved by the service container and registered with Artisan:

        SendEmails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        //每天半夜给爷👴执行下面这个计划调度
        $schedule->call(function () {
            DB::table('recent_users')->delete();
        })->daily();
        //带有__invoke方法的类的实例【invokable 实例】，可以这样调：
        $schedule->call(new DeleteRecentUsers)->daily();


        //计划执行 artisan 命令
        $schedule->command('emails:send Taylor --force')->daily();

        //自定义的command
        $schedule->command(EmailsCommand::class, ['Taylor', '--force'])->daily();

        //计划执行将queue job dispatch加入到默认queue中
        $schedule->job(new Heartbeat)->everyFiveMinutes();

        //Dispatch Heartbeat job到 heartbeats queue...
        $schedule->job(new Heartbeat, 'heartbeats')->everyFiveMinutes();

        //执行shell命令
        $schedule->exec('node /home/forge/script.js')->daily();


        //防止一个命令在执行中又开始执行另一个。就是一次运行一个的意思。原理就是加锁
        $schedule->command('email:send')
            ->withoutOverlapping(
                10//By default, the lock will expire after 24 hours: 10 means 10minutes
            );

        //只在一个机器上执行【如果你的应用分布放置多个机器】这样限制一个机器执行就OK了
        //应用必须使用【database，memcached，redis】做默认【cache驱动】才能使用本功能
        $schedule->command('report:generate')
            ->fridays()
            ->at('17:00')
            ->onOneServer();

        //放后台执行
        $schedule->command('analytics:report')
            ->daily()
            ->runInBackground();//一般只会通过command或exec执行

        //维护模式下 命令也会执行
        $schedule->command('emails:send')->evenInMaintenanceMode();


        //输出到文件
        $filePath = '';
        $schedule->command('emails:send')
            ->daily()
            ->sendOutputTo($filePath);

        //追加到文件
        $schedule->command('emails:send')
            ->daily()
            ->appendOutputTo($filePath);

        //如果配置好了email发送服务
        //可以把输出发送到指定邮箱
        $schedule->command('foo')
            ->daily()
            ->sendOutputTo($filePath)
            ->emailOutputTo('foo@example.com');

        //只把错误信息发到指定邮箱
        $schedule->command('foo')
            ->daily()
            ->emailOutputOnFailure('foo@example.com');


        //可以在任务执行前后安插逻辑
        $schedule->command('emails:send')
            ->daily()
            ->before(function () {
                // Task is about to start...
            })->after(function () {
                // Task is complete...
            });

        //可以在任务成功和失败后安插逻辑
        $schedule->command('emails:send')
            ->daily()
            ->onSuccess(function () {
                // The task succeeded...
            })->onFailure(function () {
                // The task is failed...
            });


        $url = '';
        //可以在任务执行前或完成后ping一个URL
        $schedule->command('emails:send')
            ->daily()
            ->pingBefore($url)
            ->thenPing($url);

        //条件为真才ping
        $condition = true;
        $schedule->command('emails:send')
            ->daily()
            ->pingBeforeIf($condition, $url)
            ->thenPingIf($condition, $url);


        //任务执行成功或失败后ping url
        $schedule->command('emails:send')
            ->daily()
            ->pingOnSuccess($url)
            ->pingOnFailure($url);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        // Because of the load method call in your console kernel's commands method,
        // all commands within the app/Console/Commands directory will automatically be registered with Artisan.
        // In fact,
        // you are free to make additional calls to the load method to scan other directories for Artisan commands:
        $this->load(__DIR__ . '/MoreCommands');

        require base_path('routes/console.php');
    }
}
