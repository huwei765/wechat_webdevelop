<?php 

// 启动调试
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 载入配置和依赖
require_once 'config.php';
require_once 'WechatRes.class.php';

// 启动session
session_name('wechat');
session_start();

// 初始化WeChatRes对象
$wechat = new WechatRes(APPID, SECRET);

$stoken = isset($_SESSION['token']) ? $_SESSION['token'] : false;//得到session中的token
$status = $wechat->getStatus();//得到回调地址的status
$code = $wechat->getCode();//得到回调地址的code

if ($stoken && $wechat->verifyToken($stoken->access_token, $stoken->openid)) {
    //session中的token有效则为其token续命(可选)
    $_SESSION['token'] = $stoken = $wechat->refreshToken($stoken->refresh_token);
    if (!$stoken) {
        $err = $wechat->getError();
        trigger_error($err->code.':'.$err->msg);
        exit;
    }
} elseif ($code) {
    if ($res = $wechat->getAccessInfo($code, SCOPE_INFO)) {
        // 有code则获取access_token
        $_SESSION['token'] = $stoken = $res;
    } else {
        // 失败则显示异常信息
        $err = $wechat->getError();
        trigger_error($err->code.':'.$err->msg);
        exit;
    }
} else {
    // 没有code则新请求一个
    $wechat->requestCode('http://wechat.skroot.cc/index.php', SCOPE_INFO, 'access');
}

// 得到用户详细信息
$user = $wechat->getUserInfo($stoken->access_token, $stoken->openid);

require 'index.tpl.php';// 引入模板文件进行用户信息显示