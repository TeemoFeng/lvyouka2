 function uploadimg(res) {
	
	
	var result;
	var dataArr = []; // 储存所选图片的结果(文件名和base64数据)  
	var fd; //FormData方式发送请求    
	if(typeof FileReader === 'undefined') {
		alert("抱歉，你的浏览器不支持 FileReader");
		res.setAttribute('disabled', 'disabled');
		
	} else {
		res.addEventListener('change', readFile, false);
	}

	function readFile() {
		fd = new FormData();
		var iLen = this.files.length;
		var index = 0;
		var currentReViewImgIndex = 0;
		for(var i = 0; i < iLen; i++) {
			if(!res['value'].match(/.jpg|.gif|.png|.jpeg|.bmp/i)) {　　 //判断上传文件格式    
				return alert("上传的图片格式不正确，请重新选择");
			}
			var reader = new FileReader();
			reader.index = i;
			fd.append(i, this.files[i]);
			reader.readAsDataURL(this.files[i]); //转成base64    
			reader.fileName = this.files[i].name;
			reader.onload = function(e) {
				var imgMsg = {
					name: this.fileName, 
					base64: this.result  
				}
				$(res).siblings().attr('src', this.result)
				$(res).attr('value', this.result)
			}
		}
	}	
}