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
                    layer.msg(res.msg, {icon: 1});
                    setTimeout(function () {
                        location.href = res.url
                    },1200)
                }else if(res.code == 0) {
                    layer.msg(res.msg, {icon: 2});
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
                    layer.msg(res.msg, {icon: 1});
                    setTimeout(function () {
                        location.href = res.url
                    },1200)
                }else if(res.code == 0) {
                    layer.msg(res.msg, {icon: 2});
                }
            },
            error:function () {
                layer.msg(未知错误, {icon: 0});
            }
        })
    }

}

function modifyByKeys(url,key,status,returnArr = []) {
    if(key != ''){
         returnArr.push(key)
    }else{
        var KeyClassName = 'Keys'
        var Keys = $('.'+KeyClassName)
        $.each(Keys,function (key,value) {
            if (Keys.eq(key).is(':checked')){
                returnArr.push(Keys.eq(key).attr('Key'))
            }
        })
    }
    data = {
        id:returnArr,
        status:status
    }
    ajax_Submit(url,data,'post')
}