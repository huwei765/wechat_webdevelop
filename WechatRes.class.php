<?php

/**
 * 微信网页开发库
 * dependent: config.php
 * @author leave <leave@skroot.cc>
 * @version 0.1 2017-03-10
 * 
 */
class WechatRes
{
	private $appid;// wechat appid
	private $secret; // wechat secret

	private $errorInfo; // 错误信息对象

	/**
	 * 初始化WechatRes类
	 * @param string $appid  APPID wechat
	 * @param string $secret SECRET wechat
	 */
	public function __construct($appid, $secret)
	{
		$this->appid  = $appid;
		$this->secret = $secret;
	}

	/**
	 * 通过code获取access_token、超时、刷新token，openid等
	 * @param  string $code 一般是从requestCode=>getCode得到的code
	 * @return object|bool  成功返回access object，失败返回false
	 * @example {
     *            "access_token":"ACCESS_TOKEN",
     *            "expires_in":7200,
     *            "refresh_token":"REFRESH_TOKEN",
     *            "openid":"OPENID",
     *            "scope":"SCOPE"
	 *           }
	 */
	public function getAccessInfo($code)
	{
		return $this->getToken(TYPE_REQUEST, $code);
	}

	/**
	 * 请求token的内部公共函数
	 * @param  使用类型 $type          默认TYPE_REQUEST，可选TYPE_REFRESH，首次获取还是刷新token
	 * @param  [type] $code          如果type为TYPE_REQUEST，则需要提供code
	 * @param  [type] $refresh_token 如果type为TYPE_REFRESH，则需要提供refresh_token
	 * @return object|bool  成功返回access object，失败返回false
	 * @example {
     *            "access_token":"ACCESS_TOKEN",
     *            "expires_in":7200,
     *            "refresh_token":"REFRESH_TOKEN",
     *            "openid":"OPENID",
     *            "scope":"SCOPE"
	 *           }
	 */
	private function getToken($type = TYPE_REQUEST, $code =NULL, $refresh_token = NULL)
	{
		// 根据访问类型得出API地址
		if ($type == TYPE_REQUEST) { // 若为首次请求token
			// 获取API地址
			$url = sprintf(API_ACCESS_TOKEN, $this->appid, $this->secret, $code);
		} elseif ($type == TYPE_REFRESH) { // 若为刷新token
			// 获取API地址
			$url = sprintf(API_REFRESH, $this->appid, $refresh_token);
		} else {
			$this->setError(2, '无效的type:'.$type);
			return false;
		}
		// 获取access_token
		if ($json = $this->getJson($url)) {
			return $this->json2obj($json);// 返回json data对象
		} else {
			// 若失败则返回，因getJson()若已失败则set错误
			return false;
		}
	}

	/**
	 * 将一个json数字转换成对象
	 * @param  array $json json处理过后的数组
	 * @return object       对象
	 */
	private function json2obj($json)
	{
		$obj = new \stdClass;
		foreach ($json as $k => $v) {
			$obj->$k = $v;
		}
		// 返回一个object(⬆将json转换为object)
		return $obj;
	}

	/**
	 * 拉取用户信息。!!! (需scope为 snsapi_userinfo) !!!
	 * @param  string $access_token access_token
	 * @param  string $openid       用户的唯一标识
	 * @param  string $lang         返回的语言，默认简体中文
	 * @return object|bool          成功返回object，失败返回false
	 * @example
	 * {
	 *   "openid":" OPENID",
	 *   " nickname": NICKNAME,
	 *   "sex":"1",
	 *   "province":"PROVINCE"
	 *   "city":"CITY",
	 *   "country":"COUNTRY",
	 *   "headimgurl":    "http://wx.qlogo.cn/mmopen/g3M...", 
	 *	 "privilege":[
	 *	     "PRIVILEGE1"
	 *	     "PRIVILEGE2"
	 *    ],
	 *    "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
	 *}
	 */
	public function getUserInfo($access_token, $openid, $lang = 'zh_CN')
	{
		// 获取API地址
		$url = sprintf(API_USER_DETAIL, $access_token, $openid, $lang);
		// 获取用户信息
		if ($json = $this->getJson($url)) {
			return $this->json2obj($json);// 返回json data对象
		} else {
			return false;
		}
	}

	/**
	 * 验证access_token有效性
	 * @param  string $access_token access token
	 * @param  string $openid       用户唯一id
	 * @return bool                 有效返回true，无效返回false
	 */
	public function verifyToken($access_token, $openid)
	{
		// 获取API地址
		$url = sprintf(API_VERIFY_TOKEN, $access_token, $openid);
		// 验证token有效性
		if ($json = $this->getJson($url)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 刷新access_token
	 * @param  string $refresh_token 用作refresh的token
	 * @return object|bool  成功返回object，失败返回false
	 *         (透过$this->getError()可得到错误信息)
	 * @example 
	 * {
     *     "access_token":"ACCESS_TOKEN",
     *     "expires_in":7200,
     *     "refresh_token":"REFRESH_TOKEN",
     *     "openid":"OPENID",
     *     "scope":"SCOPE"
	 *  }
	 */
	public function refreshToken($refresh_token)
	{
		// 调用内部公共函数即可完成刷新token
		return $this->getToken(TYPE_REFRESH, NULL, $refresh_token);
	}

	/**
	 * 从URL中获取JSON数据
	 * @param  string $url 网址
	 * @return array|bool 成功返回数组，失败返回false
	 *         若失败可通过$this->errorInfo()获取错误信息
	 */
	private function getJson($url)
	{
		$res = json_decode($this->get($url));
		// 若解析失败则设置错误信息并返回false
		if (!$res) {
			$this->setError(1, 'json解析出错，url:'.$url);
			return false;
		} elseif (isset($res->errcode) && $res->errcode != 0) {
			// 如果发现返回结果有错误
			$this->setError($res->errcode, $res->errmsg);
			return false;
		} else {
			return $res;
		}
	}

	/**
	 * 设置一个错误信息
	 * @param integer $code 错误代码
	 * @param string  $msg  错误讯息
	 */
	private function setError($code, $msg)
	{
		$error = new \stdClass;
		$error->code = $code;
		$error->msg  = $msg;
		$this->errorInfo = $error;
	}

	/**
	 * 获取上一次个操作发生的错误
	 * @return object 错误对象 {code, msg}
	 */
	public function getError()
	{
		return $this->errorInfo;
	}

	/**
	 * 跳转到WeChat的授权页以请求code 「一次性」
	 * 将会访问WeChat授权URL 
	 * @param string $toUrl 回调地址 绝对地址
	 * @param string $scope 可空，获取用户信息的等级 默认SCOPE_BASE，可选SCOPE_INFO
	 * @param string $status 可空，状态，将会伴随着回调地址返回。
	 * @return void   将会跳转到WeChat的验证页，待用户完成操作后跳转到回调页
	 *
	 */
	public function requestCode($toUrl, $scope = SCOPE_BASE, $status = '')
	{
		// 获取API地址
		$url = sprintf(API_CODE, $this->appid, urlencode($toUrl), $scope, $status);
		// 重定向到授权页(或者静默跳回)
		header('Location:'.$url);
		exit;
	}

	/**
	 * 获取code，从$_GET中检查
	 * 一般是在回调页面使用本函数
	 * @return string|boolean 有返回真 否则返回假
	 */
	public function getCode()
	{
		return isset($_GET['code']) ? $_GET['code'] : false;
	}

	/**
	 * 获取status，从$_GET中检查
	 * 一般是在回调页面使用本函数
	 * @return string|boolean 有返回真 否则返回假
	 */
	public function getStatus()
	{
		return isset($_GET['status']) ? $_GET['status'] : false;
	}

	/**
	 * get请求一个URL
	 * @param  string $url 网址
	 * @return string      内容
	 */
	private static function get($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);// 返回
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}