<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 入口自动绑定模块
    'auto_bind_module'       => false,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => 'htmlspecialchars',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 应用类库后缀
    'class_suffix'           => false,
    // 控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'h5',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => true,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 路由使用完整匹配
    'route_complete_match'   => false,
    // 路由配置文件（支持配置多个）
    'route_config_file'      => ['route'],
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',
    // 表单ajax伪装变量
    'var_ajax'               => '_ajax',
    // 表单pjax伪装变量
    'var_pjax'               => '_pjax',
    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache'          => false,
    // 请求缓存有效期
    'request_cache_expire'   => null,
    // 全局请求缓存排除规则
    'request_cache_except'   => [],

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 默认模板渲染规则 1 解析为小写+下划线 2 全部转换小写
        'auto_rule'    => 1,
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}}',
        // 标签库标签开始标记
        'taglib_begin' => '{{',
        // 标签库标签结束标记
        'taglib_end'   => '}}',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => [],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => [],
    ],

    // +----------------------------------------------------------------------
    // | Trace设置 开启 app_trace 后 有效
    // +----------------------------------------------------------------------
    'trace'                  => [
        // 内置Html Console 支持扩展
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap2',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
    'captcha' =>[
    'imageH' => 50,
       'codeSet'=>'0123456789',
    'imageW' => 200,
    'length' => 4
    ]
      ,
    'oauth' => [
        'appid'     => 'wx86f2c25b07aa9f93',
        'appsecret'     => '0d9c4906445b1660aaa408e5bd7c0d49',
    ],
    'alipay' => [
        //应用ID,您的APPID。
        'app_id' => "2018120562440444",

        //商户私钥，您的原始格式RSA私钥
        'merchant_private_key' => "MIIEpQIBAAKCAQEAz4mNEbrjAyavuCGHncjOC4I7cPi9gC15eTq1H+2In9y9i+iqC4MZZgt66zsRmDsyYRTR1ZUK8XMVhmvXziRNWJcSz7cjFORAtzupuADfgQOsvklQvet4VjcASKqkocB6cu4TNRi23VBwWXnvK0C1ushvN9OFVcEOzcJmyZPm2J+KusN04Z4mDzSGy6pv+/S6G5qTYDRO9yu6N5lORRR4aEqMAaZu80ixkD+E0a4fk8ELb5gvBCvt/29JBckoC7g5Jg/nZSlCIvXo2h7Z8QBAjQAVo03siIKUwpk/iNtqGtCt5oku4AugzZ+vAAZcmt7WicLMsqGMVTdaWDHeojQQtwIDAQABAoIBAQCJ2ohF2qmoEi5uVHdMq3GR23O4WsElPw+NIx3kk1dJOMr/ABDTjMV2LvH7BkVtpQSVz8qB4HpgX11Q6Jl0aFCoI9Fu/+rhmawTCiJ2Ar5zaAl6bCChxqMsQWSC4DZy6vNrHBDOGBh/cUrvZDsls9oCs9iMcIgEqjQ3IIY+J2wTPtQId57EgLom/U3aZzakUPUONFWRSGPLyiuQAI9toM+qwkkSAJ+LglbYL8MW8WP9NpdEFiBPnvIWycSvcwb8E/qt2wQASMvXIq4Swvh/46Qt3KBStoESkTJPMvN63PCiiPEAPCxt+pGsWg/irr/PgnDDwSgBxb/ZNIOzuhkUlzWBAoGBAOzB5ubHreFpWoSrgOQ7pmqBeNzmJ8ih7RHU3hc49xorOsX28wDTlJtObhyU1i0K58y9SE5rUSnFMMOnC/A6LYRmwRVvRRt5vS5ccN8nHhJ/eW61oGOf6h3bCDPO2T9wMDzfoMqHdJlzn348TAAdUMi5dxYI3nzxQIKe5/k4LZoxAoGBAOBnrhoP36s7phaXoQNAG7qEyckS6bRXwAmj45t0LcGIBIfYcishOKv9O7ws0lTqaBLDc7lTuZzXBn4SIGxlxA/vVOzZ8PhkTuFFkkAeDDcHnfNPrmgKboSXsucvUJhVXtC2GYECAglbe/d7WyA2IEVWn7cdykuvlprkX0AOQrdnAoGBAJFwT+qm8T7OXEexnz0VE5bLsDZqwDe0mRBiJog3ezw9IB6qI/72+owpMuUl3SfQUjLod/mMXVB+jQUzodbRtlJmWOhU8Sv+reND8CZ1Pjj4y9zhgASTINt0SOaig7w/q7JJYdnoOg1mBK0kVz+ewph7rhcAHcS84vcarL/g7cqRAoGADme5bmTcd9KBa+vZ4yqHXSbPCUBUjkYfxr6lisIfec/wcoP7eDdOuwOrhP3flqHhgmrXj+sG/EF1Yjxppmu19UvoyLeI13kg8ycTJ1iGcjXj9s2DpZwd0hcm3d5UryKzznQSGQz28oDT6WQaymuPEMRpxkh8RvWDlnfYgXUo5TsCgYEAsI9LqQ2UF9BaKMwr6N4+ycoseQwRQSLYIpqBrgHl/jzVM8lekQbRMIdhwbp0a2oOQ85ljA+r5Yd8h2jcpI6aOehrJc3G2aODoEajXM7dP8C2rr0mersMMhDLDRyTuFy2FrvVBx2KpukdPI/QFY4TwcmWU4KOphXaa8JNLqnX+8s=",
        //购卡异步通知地址
        'notify_url' => 'http://' . $_SERVER['SERVER_NAME'] .'/h5/alipay/notify_url',

        //购卡同步跳转
        'return_url' => 'http://' . $_SERVER['SERVER_NAME'] .'/h5/alipay/return_url',

        'notify_trip' => 'http://' . $_SERVER['SERVER_NAME'] .'/h5/alipay/notify_trip',
//预约同步跳转
        'return_trip' => 'http://' . $_SERVER['SERVER_NAME'] .'/h5/alipay/return_trip',

        //编码格式
        'charset' => "UTF-8",

        //签名方式
        'sign_type'=>"RSA2",

        //支付宝网关
        'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

        //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAgm1b0h+5y0he5S/0vdPlkCz2vLYAeWq9wsHjcIq0aTkziEvEZeT2NrrQul4Qw2I199jE9DlNi30ir20VZ6S1HOq3SxJ+QQozVtkCBZV2XW+eb34Wnx37RN8mBPg4y145+j5T3XEsVzXIxLpojt9iBvGotym3XMZl1I7w67vL5E/mdsCwpR1eGMgNr5rOTdvOtUxMOKBzX4Rb7Gkx1MOcOz40QHyCnLTFzSE+YOxAYUAqfdi02UE8qBa0GmIamE/z63tP9glenyOO2DBprvzGiVLkJyaxSl1L2Pg+fiZhAFioifq2bm9+/Qs+WMfIWq0M5wpSD5v0kNzxQSYDCvCRmQIDAQAB",
    ],
];
