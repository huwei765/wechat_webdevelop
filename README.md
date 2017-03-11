# 微信网页开发基础操作PHP类

![](https://image.hongkong.skroot.cc/images/2017/03/11/Jietu20170311-193945e130f.png)

`WechatRes.class.php`文件封装了基本的微信网页开发经常用到的一些操作。
- 比如请求用户授权
- 获取Code
- 获取Access_Token
- 为Access_Token续命
- 检查Access_Token有效性
并且将返回Object供您使用。

>使用时请配置`config.php`中的`APPID`和`SECRET`值（在微信公众号后台得到）
