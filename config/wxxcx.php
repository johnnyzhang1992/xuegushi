<?php

return [
	/*
	 * 小程序APPID
	 */
    'appid' => 'wx4278af156928b4f6',
    /*
     * 小程序Secret
     */
    'secret' => '507c7ca4ab315886df480239c934bded',
    /*
     * 小程序登录凭证 code 获取 session_key 和 openid 地址，不需要改动
     */
    'code2session_url' => "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
    /*
    * 获取小程序全局唯一后台接口调用凭据（access_token）
    */
    'accessToken_url' => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s",
    /*
     * 获取小程序码的链接
     */
    'wxacode_url' => "https://api.weixin.qq.com/wxa/getwxacode?access_token=%s"
];
