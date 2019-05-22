function ajax_Submit(url,data,type,file = 0) {
    //return false;
    if (file == 1){
        $.ajax({
            url:url,
            data:data,
            type:type,
            dataType:'json',
            contentType:false,
            processData:false,
            success:function (res) {
                if(res.code == 1){
                    mui.toast(res.msg)
                    setTimeout(function () {
                        location.href = res.url
                    },res.wait*1000)
                }else if(res.code == 0) {
                    mui.toast(res.msg)
                }
            },
            error:function () {
                layer.msg(未知错误, {icon: 0});
            }
        })
    } else{
        $.ajax({
            url:url,
            data:data,
            type:type,
            dataType:'json',
            success:function (res) {
                console.log(res)
                if(res.code == 1){
                    mui.toast(res.msg)
                    setTimeout(function () {
                        location.href = res.url
                    },res.wait*1000)
                }else if(res.code == 0) {
                    mui.toast(res.msg)
                }
            },
            error:function () {
                layer.msg(未知错误, {icon: 0});
            }
        })
    }
}