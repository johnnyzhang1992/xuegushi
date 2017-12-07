<?php

return array(


    /*
	|--------------------------------------------------------------------------
	| site name
	|--------------------------------------------------------------------------
    |
    |
    */
    'default_site_name' => env('SEO_DEFAULT_SITE_NAME','学古诗'),
    'default_admin_site_name' => env('SEO_DEFAULT_ADMIN_SITE_NAME','学古诗-管理后台'),

    /*
	|--------------------------------------------------------------------------
	| defautl meta data
	|--------------------------------------------------------------------------
    |
    |
    */
    'default_keywords' => env('SEO_DEFAULT_KEYWORDS', '古诗文,古诗文网,诗词助手,古诗文助手,古诗文小助手,古诗,古诗大全,经典古诗文,文言文,飞花令,唐诗,唐诗宋词,宋词,学古诗'),
    'default_description' => env('SEO_DEFAULT_DESCRIPTION', '学古诗网,诗词助手。收集古代诗词曲文言文，专注于古诗文服务，致力于让古诗文爱好者更便捷地学习及获取古诗文相关资料。'),

    /* not tracking ip */
    'exclude' => [
        'ip' => [
            '67.85.30.134',
            '59.125.182.207',
            '69.249.38.235',
            '183.13.99.73',
        ],
    ],

    'default_sub_title' => '古诗文小助手',


    'google_tag_manager' =>[
        'gtm_code' => env('GOOGLE_TAG_MANAGER_CODE','GTM-T4PSDDL'),
    ],
);
