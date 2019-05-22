<?php
$config = array (	
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
);