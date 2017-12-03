<!DOCTYPE html>
<html lang="en" style="height:auto;">
<head>
    <meta charset="UTF-8">
    <title>欢迎邮件</title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <style>
        td, input, button, select, body {
            font-family: Helvetica, 'Microsoft Yahei', verdana;
        }
        a.item{
            text-decoration:none;
            font-size:20px;
            color: #337ab7;
        }
        a{
            text-decoration:none;
            color: #337ab7;
        }
    </style>
</head>
<body tabindex="0" style="height:auto;padding:0;margin:0;border:0;overflow:hidden">
<div class="mail_area mail_pc" id="app_mail" style="margin-top: 15px;">
    <div>
        <table align="center" style="font-family:Microsoft YaHei,Simsun;border-left:1px solid #dbdbdb;border-right:1px solid #dbdbdb;border-top:1px solid #dbdbdb;color:#fff;width:100%;max-width:650px;_width:650px;*width:650px;table-layout:fixed;" bgcolor="#ffffff" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td colspan="3" style="text-align:center;">
                    <a target="_blank" style="display:block;width:100%;padding-top:20px;text-decoration:none;font-size:25px;color: #337ab7;vertical-align:top;" href="{{ url('/') }}" draggable="false">
                        <span style="vertical-align:top;border:0;">学古诗 - 古诗文小助手</span>
                    </a>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="height:50px;line-height:50px;text-align:center;">
                    <a href="{{ url('/') }}" target="_blank" style="display:block;height:50px;width:100%;text-decoration:none;font-size:16px;color:#222222;vertical-align:top;" draggable="false">
                        <span style="line-height:50px;vertical-align:top;display:inline-block;font-family:Microsoft YaHei,Simsun;font-size:15px;">7 万+古诗文供您学习</span></a>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="border-bottom:1px solid #dcdcdc;">
                    <table style="font-family:Microsoft YaHei,Simsun;width:100%;max-width:650px;_width:650px;*width:650px;table-layout:fixed;text-align:center;margin:10px 0" bgcolor="#ffffff" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td width="25%">
                                <a class="item" target="_blank" href="{{ url('/poem?type=诗') }}" draggable="false">
                                    {{--<img style="width:72%;max-width:77px;border:0;display:inline-block" src="https://yanxuan.nosdn.127.net/15052130840920519.png" draggable="false">--}}
                                    <span>诗</span>
                                </a>
                            </td>
                            <td width="25%">
                                <a  class="item" target="_blank" href="{{ url('/poem?type=词') }}" draggable="false">
                                    {{--<img style="width:72%;max-width:77px;border:0;display:inline-block" src="https://yanxuan.nosdn.127.net/15052130840920519.png" draggable="false">--}}
                                    <span>词</span>
                                </a>
                            </td>
                            <td width="25%">
                                <a  class="item" target="_blank" href="{{ url('/poem?type=曲') }}" draggable="false">
                                    {{--<img style="width:72%;max-width:77px;border:0;display:inline-block" src="https://yanxuan.nosdn.127.net/15052130840920519.png" draggable="false">--}}
                                    <span>曲</span>
                                </a>
                            </td>
                            <td width="25%">
                                <a  class="item" target="_blank" href="{{ url('/poem?type=文言文') }}" draggable="false">
                                    {{--<img style="width:72%;max-width:77px;border:0;display:inline-block" src="https://yanxuan.nosdn.127.net/15052130840920519.png" draggable="false">--}}
                                    <span>文言文</span>
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            {{--<tr>--}}
                {{--<td colspan="3" style="height:10px;"></td>--}}
            {{--</tr>--}}
            </tbody>
        </table>
    </div>
    <div>
        <table align="center" style="font-family:Microsoft YaHei,Simsun;border-left:1px solid #dbdbdb;border-right:1px solid #dbdbdb;border-top:1px solid #dbdbdb;width:100%;max-width:650px;_width:650px;*width:650px;table-layout:fixed;" bgcolor="#ffffff" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td colspan="3" style="border-bottom:1px solid #dcdcdc;">
                    <table style="font-family:Microsoft YaHei,Simsun;width:100%;max-width:650px;_width:650px;*width:650px;table-layout:fixed;text-align:center;margin:10px 0" bgcolor="#ffffff" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td>
                                <div class="mail_msg" style="text-align: left;padding: 0 60px;">
                                    <p>你好，{{ @$user_name }}。</p>
                                    <p>您收到此邮件，说明您已经注册成功，成为了我们古诗文学习大军中的一员。我们一起加油吧！</p>
                                    <p>你的登录邮箱为：{{ @$email }}。请您记住此邮箱,以后将作为您登录以及找回密码的凭证。</p>
                                    <p>下面就开始你的学习之旅吧!  <a href="{{ url('/') }}" style="padding: 6px 12px; border: 1px solid #337ab7;background-color: #eeee;">点我开始</a></p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p style="text-align: left;padding: 0 60px;">
                                    <a href="https://xuegushi.cn/join" class="footer-item" target="_blank">加入我们</a>
                                    <span class="footer-dot">⋅</span>
                                    <a href="https://xuegushi.cn/contact" class="footer-item" target="_blank">联系我们</a>
                                    <span class="footer-dot">⋅</span>
                                    <a href="https://xuegushi.cn/about" class="footer-item" target="_blank">免责声明</a>
                                </p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
        <div style="max-width: 650px;margin: 0 auto;">
            <div class="mail_info" style="margin-top: 20px; padding-left: 20px;">
                <strong>Johnny Zhang</strong>  <small>学古诗网管理员</small>
            </div>
        </div>
    </div>
</div>
</body>
</html>