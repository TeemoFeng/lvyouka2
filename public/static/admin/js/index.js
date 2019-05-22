
;
$(function(){

	$(".all-check").click(function () {
	    $(".shell-check").prop("checked", this.checked);
	});
	$(".shell-check").click(function () {
	    var option = $(".shell-check");
	    option.each(function (i) {
	        if (!this.checked) {
	            $(".all-check").prop("checked", false);
	            return false;
	        } else {
	            $(".all-check").prop("checked", true);
	        }
	    });
	});
  

})

