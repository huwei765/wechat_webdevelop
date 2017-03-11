<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>WeChat網頁開發範例</title>
    <link href="http://cdn.bootcss.com/weui/1.1.1/style/weui.min.css" rel="stylesheet">
    <link rel="stylesheet" href="example.css"/>
</head>
<body>
    <div class="page input js_show">
        <div class="page__hd">
            <h1 class="page__title">微信網頁開發範例</h1>
            <p class="page__desc">這裡顯示您的基礎訊息</p>
        </div>
        <div class="weui-form-preview">
            <div class="weui-media-box__hd">
                <img class="weui-media-box__thumb" width="40%" src='<?=$user->headimgurl?>' />
            </div>
        </div>
        <div class="page__bd">
                        <div class="weui-form-preview__hd">
                            <div class="weui-form-preview__item">
                                <label class="weui-form-preview__label">姓名</label>
                                <em class="weui-form-preview__value"><?=$user->nickname?></em>
                            </div>
                        </div>
                        <div class="weui-form-preview__bd">
                            <div class="weui-form-preview__item">
                                <label class="weui-form-preview__label">性别</label>
                                <span class="weui-form-preview__value"><?=$user->sex ? '男' : '女'?></span>
                            </div>
                            <div class="weui-form-preview__item">
                                <label class="weui-form-preview__label">国家</label>
                                <span class="weui-form-preview__value"><?=isset($user->country) ? $user->country : '没有' ?></span>
                            </div>
                            <div class="weui-form-preview__item">
                                <label class="weui-form-preview__label">省份</label>
                                <span class="weui-form-preview__value"><?=$user->province ?: '没有' ?></span>
                            </div>
                            <div class="weui-form-preview__item">
                                <label class="weui-form-preview__label">城市</label>
                                <span class="weui-form-preview__value"><?=$user->city ?: '没有' ?></span>
                            </div>
                            <div class="weui-form-preview__item">
                                <label class="weui-form-preview__label">UniqueID</label>
                                <span class="weui-form-preview__value"><?=isset($user->unionid) ? $user->unionid : '没有'?></span>
                            </div>
                            <div class="weui-form-preview__item">
                                <label class="weui-form-preview__label">OpenID</label>
                                <span class="weui-form-preview__value"><?=$user->openid ?></span>
                            </div>
                        </div>
                        <div class="weui-form-preview__ft">
                            <a class="weui-form-preview__btn weui-form-preview__btn_primary" href="javascript:">操作</a>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="page__ft">
                    <a href="javascript:home()"><img src="./images/icon_footer_link.png"></a>
                </div>
            </div>
            <div class="page__ft">
                <div class="weui-cells__tips">
                    <a href="https://liwei2.com">舞慟01</a>
                </div>
            </div>
</body>
</html>