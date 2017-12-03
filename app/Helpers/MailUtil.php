<?php

namespace App\Helpers;

use Mail;
use Config;
use DB;
use Log;
use Exception;
use Illuminate\Http\Request;

class MailUtil
{

    /**
     *
     * Singleton
     */
    public function send(){
        $name = '王宝花';
        // Mail::send()的返回值为空，所以可以其他方法进行判断
        Mail::send('emails.test',
            [
                'name'=>$name
            ],
            function($m){
//                $m->from('me@johnnyzhang.cn','学古诗网管理员');
                $m->to('1113638674@qq.com','小小梦工场');
                $m->subject('邮件测试');
            });
        // 返回的一个错误数组，利用此可以判断是否发送成功
//        dd(Mail::failures());
    }
    /**
     * 用户注册欢迎邮件
     * @param $user_name
     * @param  $email
     */
    public static function UserCreate($user_name,$email){
        Mail::send('emails.notice.welcome',
            [
                'user_name'=>$user_name,
                'email' => $email
            ],
            function($m) use ($email,$user_name){
                $m->from('info@xuegushi.cn','古诗文小助手');
                $m->to($email,$user_name);
                $m->subject('【学古诗】欢迎使用学古诗网');
            });
    }

}