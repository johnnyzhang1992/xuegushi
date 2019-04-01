# 一个关于古诗文查询与学习的网站

## 技术

* 使用了laravel 5.2

* 后台管理使用了 [laraadmin](https://github.com/dwijitsolutions/laraadmin)

## 具体文件架构

### views文件（html模板文件）

存放在 `resources -> views` 下面。

其中各文件夹的用途：

- auth 是注册部分模板
- emails 是注册后发送邮件的模板
- errors 是网站报错时的模板
- frontend 是网站前端模板[学古诗](xuegushi.cn)
- la 是网站后台管理模板，使用的是第三方的 `laraadmin`
- people 是注册用户的个人页面部分
- zhuan 是古诗专栏部分的模板 [古诗专栏](zhuanlan.xuegushi.cn)

### js 和 css 文件

这两部分的文件同样在 `resources` 文件下，第一部分`assets` 就是了。

- js 目录下，是网站所用到的 js 文件
- sass 目录下存放着 scss 文件。

这两部分都不是直接使用的。需要打包合并以及压缩后才是网站真实使用的文件。

打包后的文件会 `public` 文件下，会生成 `js` 和 `css` 文件夹。

具体的打包过程，参考项目根目录下面的 `gulpfile.js` 文件。需要用到 gulp 的知识。

## 问题答疑

- 是网站全部源码吗？

不是。有些生产站的配置文件，没有跟新过来。涉及隐私

- 怎么才能跑起来？

1. 先去好好学学 laravel 和 composer 的知识，知道文件结构和具体的命令。知道怎么安装依赖。
2. 其次学习 gulp 的知道，知道怎么打包文件。
3. 服务器要安装 node 哦，因为 gulp 需要 node 才能跑起来。

做完上面的三个步骤，sorry 网站可以跑起来了，但是会报错。(尴尬

因为 controller 拿取数据时，没有对应的数据库支持，肯定会报错的。

- 数据库能给你吗？

不能。需要的表结构，有时间可以导一份出来。最近没时间，正在找工作(日期：2019-04-01)

- 那放这里有啥用？

有借鉴意义啊。对于学习 laravel 框架还是有意义的。

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
