<header class="hero hero--profile xzl-user-profile-header">
    <div class="user-avatar-dropzone dz-clickable">
        <div class="hero-avatar xzl-pull-left user-avatar-edit u-relative show-avatar">
            <div class="avatar profile-avatar">
                <div class="u-relative">
                    <img class="avatar-image .xzl-size-80x80 xzl-width-80 xzl-height-80 js-profileAvatarImage" src="{{ asset(@$me->avatar) }}" alt="{{@$me->name}}">
                    <div class="edit-avatar-mask hidden"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="27px" viewBox="0 0 30 27" version="1.1" class="edit-image-div hidden">
                        <!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch -->
                        <title>icon_photo_1</title>
                        <desc>Created with Sketch.</desc>
                        <defs></defs>
                        <g id="切图" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-396.000000, -110.000000)" id="icon_photo_1">
                                <g transform="translate(393.000000, 107.000000)">
                                    <polygon id="Shape" points="0 0 36 0 36 36 0 36"></polygon>
                                    <circle id="Oval" fill="#FFFFFF" cx="18" cy="18" r="4.8"></circle>
                                    <path d="M13.5,3 L10.755,6 L6,6 C4.35,6 3,7.35 3,9 L3,27 C3,28.65 4.35,30 6,30 L30,30 C31.65,30 33,28.65 33,27 L33,9 C33,7.35 31.65,6 30,6 L25.245,6 L22.5,3 L13.5,3 L13.5,3 Z M18,25.5 C13.86,25.5 10.5,22.14 10.5,18 C10.5,13.86 13.86,10.5 18,10.5 C22.14,10.5 25.5,13.86 25.5,18 C25.5,22.14 22.14,25.5 18,25.5 L18,25.5 Z" id="Shape" fill="#FFFFFF"></path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
            </div>
        </div></div>
    <div class="other-basic-info">
        <p class="profile-name hero-title">{{@$me->name}}</p>
        <div class="hero-description user-profile-bio " id="user-profile-bio" data-text="完善自我介绍，方面大家了解你">
            @if(isset($me->about) && $me->about && mb_strlen($me->about)>0)
                {{@$me->about}}
            @else
                暂无简介
            @endif
        </div>
        <div class="user-profile-bio-count hidden">100 / 140</div>
        <textarea class="user-profile-bio-edit hidden" id="user-profile-bio-edit" placeholder="完善自我介绍，方面大家了解你(140字以内">时间会告诉你答案。</textarea>
        <div class="xzl-button-set xzl-profile-button-set">
            {{--<a class="button xzl-button-chrome-less xzl-base-color-normal-btn xzl-marign-right32 no-border" href="/u/5775582212/following">关注 0</a>--}}
            {{--<a class="button xzl-button-chrome-less xzl-base-color-normal-btn xzl-marign-right32 no-border" href="/u/5775582212/followers">被关注 0</a>--}}
            <a class="button xzl-button-chrome-less xzl-base-color-normal-btn xzl-marign-right32 no-border" href="{{ url('people/'.@$me->id.'/favorites') }}">获得赞 {{@$me->post_like_count}}</a>
            <a class="button xzl-button-chrome-less xzl-base-color-normal-btn  no-border" href="{{ url('people/'.@$me->id.'/collects') }}">被收藏 {{@$me->post_collect_count}}</a>
            {{--<div class="pc-third-accounts">--}}
            {{--<a class="button xzl-button-chrome-less xzl-marign-left32  profile-third-account-logo  weibo  button--withIcon button--withSvgIcon" href="http://weibo.com/u/2477183313" target="_blank">--}}
            {{--<span class="xzl-default-state-btn">--}}
            {{--<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22px" height="19px" viewBox="0 0 22 19" version="1.1">--}}
            {{--<!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch -->--}}
            {{--<title></title>--}}
            {{--<desc>Created with Sketch.</desc>--}}
            {{--<defs></defs>--}}
            {{--<g id="切图" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
            {{--<g transform="translate(-204.000000, -229.000000)" id="icon_mine_weibo" fill="#818181">--}}
            {{--<g transform="translate(204.000000, 229.000000)">--}}
            {{--<path d="M8.682,19.0015 C4.414,19.0015 0,16.813 0,13.1495 C0,11.311 1.0825,9.18 3.0485,7.1505 C4.9335,5.2065 7.08,3.9985 8.6505,3.9985 C9.277,3.9985 9.8005,4.1975 10.1655,4.5745 C10.5325,4.9525 10.884,5.654 10.6315,6.9015 C11.564,6.536 12.4405,6.3435 13.185,6.3435 C14.3855,6.3435 14.9715,6.8355 15.2505,7.2475 C15.6545,7.8425 15.6855,8.628 15.345,9.5845 C17.019,10.137 18.014,11.3335 18.014,12.796 C18.014,15.731 14.1815,19.0015 8.682,19.0015 L8.682,19.0015 Z M8.6505,4.9985 C7.3805,4.9985 5.418,6.143 3.767,7.8465 C1.9825,9.688 1,11.5715 1,13.1495 C1,15.535 3.8735,18.0015 8.682,18.0015 C13.844,18.0015 17.014,14.97 17.014,12.796 C17.014,11.327 15.6035,10.7175 14.9975,10.523 C14.788,10.4585 14.4975,10.368 14.3525,10.0685 C14.2055,9.764 14.3255,9.4535 14.365,9.3515 C14.547,8.8775 14.7055,8.225 14.423,7.809 C14.1625,7.424 13.6055,7.3435 13.185,7.3435 C12.485,7.3435 11.6205,7.5575 10.6845,7.9615 C10.681,7.963 10.677,7.9645 10.6735,7.966 C10.548,8.019 10.3615,8.08 10.174,8.08 C9.8615,8.08 9.694,7.916 9.622,7.8175 C9.528,7.69 9.4385,7.466 9.5445,7.1255 C9.8005,6.276 9.7655,5.5985 9.4475,5.2705 C9.2725,5.0895 9.0045,4.9985 8.6505,4.9985 L8.6505,4.9985 Z M21.51,6 C21.234,6 21.01,5.7765 21.01,5.5 C21.01,3.0185 18.9915,1 16.51,1 C16.234,1 16.01,0.7765 16.01,0.5 C16.01,0.2235 16.234,0 16.51,0 C19.543,0 22.01,2.4675 22.01,5.5 C22.01,5.7765 21.7865,6 21.51,6 L21.51,6 Z M18.01,6 C17.734,6 17.51,5.7765 17.51,5.5 C17.51,4.949 17.0615,4.5 16.51,4.5 C16.234,4.5 16.01,4.2765 16.01,4 C16.01,3.7235 16.234,3.5 16.51,3.5 C17.613,3.5 18.51,4.397 18.51,5.5 C18.51,5.7765 18.2865,6 18.01,6 L18.01,6 Z" id="Shape-Copy-3"></path>--}}
            {{--</g>--}}
            {{--</g>--}}
            {{--</g>--}}
            {{--</svg>--}}

            {{--</span>--}}
            {{--</a>--}}
            {{--</div>--}}
        </div>
        {{--<div class="mobile-third-accounts">--}}
        {{--<a class="button xzl-button-chrome-less xzl-marign-left32  profile-third-account-logo  weibo  button--withIcon button--withSvgIcon" href="http://weibo.com/u/2477183313" target="_blank">--}}
        {{--<span class="xzl-default-state-btn">--}}
        {{--<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="22px" height="19px" viewBox="0 0 22 19" version="1.1"><!-- Generator: Sketch 44.1 (41455) - http://www.bohemiancoding.com/sketch --><title></title><desc>Created with Sketch.</desc>--}}
        {{--<defs></defs><g id="切图" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g transform="translate(-204.000000, -229.000000)" id="icon_mine_weibo" fill="#818181"><g transform="translate(204.000000, 229.000000)"><path d="M8.682,19.0015 C4.414,19.0015 0,16.813 0,13.1495 C0,11.311 1.0825,9.18 3.0485,7.1505 C4.9335,5.2065 7.08,3.9985 8.6505,3.9985 C9.277,3.9985 9.8005,4.1975 10.1655,4.5745 C10.5325,4.9525 10.884,5.654 10.6315,6.9015 C11.564,6.536 12.4405,6.3435 13.185,6.3435 C14.3855,6.3435 14.9715,6.8355 15.2505,7.2475 C15.6545,7.8425 15.6855,8.628 15.345,9.5845 C17.019,10.137 18.014,11.3335 18.014,12.796 C18.014,15.731 14.1815,19.0015 8.682,19.0015 L8.682,19.0015 Z M8.6505,4.9985 C7.3805,4.9985 5.418,6.143 3.767,7.8465 C1.9825,9.688 1,11.5715 1,13.1495 C1,15.535 3.8735,18.0015 8.682,18.0015 C13.844,18.0015 17.014,14.97 17.014,12.796 C17.014,11.327 15.6035,10.7175 14.9975,10.523 C14.788,10.4585 14.4975,10.368 14.3525,10.0685 C14.2055,9.764 14.3255,9.4535 14.365,9.3515 C14.547,8.8775 14.7055,8.225 14.423,7.809 C14.1625,7.424 13.6055,7.3435 13.185,7.3435 C12.485,7.3435 11.6205,7.5575 10.6845,7.9615 C10.681,7.963 10.677,7.9645 10.6735,7.966 C10.548,8.019 10.3615,8.08 10.174,8.08 C9.8615,8.08 9.694,7.916 9.622,7.8175 C9.528,7.69 9.4385,7.466 9.5445,7.1255 C9.8005,6.276 9.7655,5.5985 9.4475,5.2705 C9.2725,5.0895 9.0045,4.9985 8.6505,4.9985 L8.6505,4.9985 Z M21.51,6 C21.234,6 21.01,5.7765 21.01,5.5 C21.01,3.0185 18.9915,1 16.51,1 C16.234,1 16.01,0.7765 16.01,0.5 C16.01,0.2235 16.234,0 16.51,0 C19.543,0 22.01,2.4675 22.01,5.5 C22.01,5.7765 21.7865,6 21.51,6 L21.51,6 Z M18.01,6 C17.734,6 17.51,5.7765 17.51,5.5 C17.51,4.949 17.0615,4.5 16.51,4.5 C16.234,4.5 16.01,4.2765 16.01,4 C16.01,3.7235 16.234,3.5 16.51,3.5 C17.613,3.5 18.51,4.397 18.51,5.5 C18.51,5.7765 18.2865,6 18.01,6 L18.01,6 Z" id="Shape-Copy-3"></path></g></g></g>--}}
        {{--</svg>--}}
        {{--</span>--}}
        {{--</a>--}}
        {{--</div>--}}
        @if (!Auth::guest() && Auth::user()->id == $me->id)
            <div class="xzl-button-set xzl-profile-button-set xzl-profile-button-set-setting  u-inlineBlock">
               <span class="followState js-followState xzl-button-set-inner">
                   <a class="btn btn-default profile-setting-btn  edit-profile-button" type="button" href="{{url('/me/setting')}}">
                       <span class="button-label  xzl-default-state-btn js-buttonLabel">编辑个人资料</span>
                   </a>
                   <button class="btn btn-default edit-profile-save-button  hidden" data-action="save-profile" data-url="/users/29047" data-profile-url="/u/5775582212">
                       <span class="button-label   js-buttonLabel">保存</span>
                   </button>
                   <button class="btn btn-default edit-profile-cancel-button  hidden" data-action="cancel-edit">
                       <span class="button-label js-buttonLabel">取消</span>
                   </button>
               </span>
            </div>
        @endif
    </div>
</header>