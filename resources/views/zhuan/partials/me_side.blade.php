<div class="Card" data-reactid="381">
    <div class="Card-header Profile-sideColumnTitle" data-reactid="382">
        <div class="Card-headerText" data-reactid="383">个人成就</div>
    </div>
    <div class="Profile-sideColumnItems" data-reactid="384">
        <div class="Profile-sideColumnItem" data-reactid="385">
            <div class="IconGraf" data-reactid="386">
                <div class="IconGraf-iconWrapper" data-reactid="387">
                    <svg viewBox="0 0 20 18" xmlns="http://www.w3.org/2000/svg" class="Icon IconGraf-icon Icon--like" style="height:16px;width:16px;" width="16" height="16" aria-hidden="true" data-reactid="388"><title data-reactid="389"></title><g data-reactid="390"><path d="M.718 7.024c-.718 0-.718.63-.718.63l.996 9.693c0 .703.718.65.718.65h1.45c.916 0 .847-.65.847-.65V7.793c-.09-.88-.853-.79-.846-.79l-2.446.02zm11.727-.05S13.2 5.396 13.6 2.89C13.765.03 11.55-.6 10.565.53c-1.014 1.232 0 2.056-4.45 5.83C5.336 6.965 5 8.01 5 8.997v6.998c-.016 1.104.49 2 1.99 2h7.586c2.097 0 2.86-1.416 2.86-1.416s2.178-5.402 2.346-5.91c1.047-3.516-1.95-3.704-1.95-3.704l-5.387.007z"></path></g></svg>
                </div>获得 378 次赞同, 25 次收藏</div>
            {{--<div class="Profile-sideColumnItemValue" data-reactid="394">获得</div>--}}
        </div>
        <div class="Profile-sideColumnItem" data-reactid="398">
            <div class="IconGraf" data-reactid="399">
                <div class="IconGraf-iconWrapper" data-reactid="400">
                    <svg width="16" height="16" viewBox="0 0 16 16" class="Icon IconGraf-icon Icon--commonEdit" style="height:16px;width:16px;" aria-hidden="true" data-reactid="401"><title data-reactid="402"></title><g data-reactid="403"><path d="M8 15.5C3.858 15.5.5 12.142.5 8 .5 3.858 3.858.5 8 .5c4.142 0 7.5 3.358 7.5 7.5 0 4.142-3.358 7.5-7.5 7.5zm3.032-11.643c-.22-.214-.574-.208-.79.013L5.1 9.173 6.778 10.8l5.142-5.303c.215-.222.21-.575-.01-.79l-.878-.85zm-6.77 7.107L4 12l1.028-.293.955-.27L4.503 10l-.242.964z" fill-rule="evenodd"></path></g></svg>
                </div>
                <a class="Profile-sideColumnItemLink" href="/people/johnnyzhang1992/logs" target="_blank" data-reactid="404"><!-- react-text: 405 -->参与 <!-- /react-text --><!-- react-text: 406 -->132<!-- /react-text --><!-- react-text: 407 --> 次公共编辑<!-- /react-text --></a>
            </div>
        </div>
    </div>
</div>
<div class="Profile-lightList" data-reactid="433">
    <a class="Profile-lightItem" href="" ><span class="Profile-lightItemName">关注的专栏</span><span class="Profile-lightItemValue">26</span></a>
    <a class="Profile-lightItem" href="" ><span class="Profile-lightItemName">收藏的文章</span><span class="Profile-lightItemValue">{{@$me->post_collect_count}}</span></a>
    <a class="Profile-lightItem" href="" ><span class="Profile-lightItemName">喜欢的文章</span><span class="Profile-lightItemValue">{{@$me->post_like_count}}</span></a>
</div>
<div class="Profile-footerOperations" data-reactid="446">个人主页被浏览 {{@$me->pv_count}} 次</div>
<footer class="footer">
    {{--<a href="#" class="footer-item" target="_blank">关于</a>--}}
    {{--<span class="footer-dot">&sdot;</span>--}}
    <a href="{{ url('join') }}" class="footer-item" target="_blank">加入</a>
    {{--<span class="footer-dot">&sdot;</span>--}}
    {{--<a href="#" class="footer-item" target="_blank">反馈</a>--}}
    <span class="footer-dot">&sdot;</span>
    <a href="{{ url('contact') }}" class="footer-item" target="_blank">联系我们</a>
    <span class="footer-dot">&sdot;</span>
    <a href="{{ url('about')  }}" class="footer-item" target="_blank">免责声明</a>
    <span class="footer-dot">&sdot;</span>
    <a href="{{ url('donate-us') }}" class="footer-item">捐赠</a>
    <br>
    <a type="button" title="微信订阅号(xuegushiwen)" class="footer-item wechat-qrcode" style="cursor: pointer">微信公众号</a>
    <span class="footer-dot">&sdot;</span>
    <a type="button" class="footer-item" style="cursor: pointer">小程序</a>
    <br>
    <span class="footer-item">&copy; 2017 学古诗网</span>
</footer>
