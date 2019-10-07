<?php

namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Http\Tools\Wechat;
                
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $schedule->call(function () {
            
//        $openid_info = DB::connection('mysql')->table("wechat_openid")->select('openid')->limit(10)->get()->toArray();
//        //dd($openid_info);
//        foreach($openid_info as $v){
//            $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->wechat->get_access_token()."";
//           $data=[
//                'touser'=>$v->openid,
//                'template_id'=>'ImOrfmPtxkRTaDWtYfPuUIGauBRdWp52UjJHcRm9Ixc',
//                'url'=>'http://www.baidu.com',
//                'data'=>[
//                    'first'=>[
//                        'value'=>'用户',
//                        'color'=>''
//                    ],
//                    'keyword1'=>[
//                        'value'=>'第一节课',
//                        'color'=>''
//                    ],
//                    'keyword2'=>[
//                        'value'=>'第二节课',
//                        'color'=>''
//                    ],
//                    'keyword3'=>[
//                        'value'=>'第三节课',
//                        'color'=>''
//                    ],
//                    'keyword4'=>[
//                        'value'=>'第四节课',
//                        'color'=>''
//                    ],
//
//                ]
//
//           ];
//          $re=$this->post($url,json_encode($data));
//           dd($re);
//        }
            
     })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
