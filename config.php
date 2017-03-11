<?php

// 公众号配置
const APPID  = '';
const SECRET = '';

// 获取Code，API (使用sprintf安全替换)
const API_CODE = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=%s&state=%s#wechat_redirect';
// 获取access_token，透过code获取access_token的API (使用sprintf安全替换)
const API_ACCESS_TOKEN = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code';
// 刷新，刷新token的API
const API_REFRESH = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=%s&grant_type=refresh_token&refresh_token=%s';
// 获取用户详细，获取用户详细信息的API
const API_USER_DETAIL = 'https://api.weixin.qq.com/sns/userinfo?access_token=%s&openid=%s&lang=%s';
// 验证，验证access_token的有效性
const API_VERIFY_TOKEN = 'https://api.weixin.qq.com/sns/auth?access_token=%s&openid=%s';

// 基本和详细用户SCOPE
const SCOPE_BASE = 'snsapi_base';
const SCOPE_INFO = 'snsapi_userinfo';

// 请求token的类型
const TYPE_REQUEST = 1;
const TYPE_REFRESH = 2;

