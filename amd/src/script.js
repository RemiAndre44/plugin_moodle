define(['jquery'], function($) {

	return {
		changeState: function(value, id) {
			/*var element = ".responseIcon"+id+value;
			$(element).click(function() {
				console.log($(element).css('color'));
				if(value == 'true' && $(element).css('color') == "rgb(0, 128, 0)"){
				    $(this).css('color', 'black');
                }else if (value == 'true') {
					$(this).replaceWith('<i class="fa fa-times"></i>');
				}else if(value == 'false' && $(element).css('color') == "rgb(255, 0, 0)") {
					$(element).css('color', "black");
				}else if(value == 'false'){
					$(element).css('color', "red");
				}else if (value == "dontknow"){
                    $(element).css('color', 'pink');
                }
			});*/
			var elementId = "#responseIcon"+id;
			var inputValId = "#input"+id;
			$(elementId).click(function() {
				if ($(inputValId).val() == 'true') {
					$(this).addClass("fa-times").removeClass("fa-check");
					$(this).css('color', 'red');
					$(inputValId).replaceWith("<input id ='input"+id+"' value='false' type='hidden'>")
				}else if($(inputValId).val() == 'false'){
					$(this).addClass("fa-question").removeClass("fa-times");
					$(this).css('color' ,'black');
					$(inputValId).replaceWith("<input id ='input"+id+"' value='dontknow' type='hidden'>")
				}else if($(inputValId).val() == 'dontknow'){
					$(this).addClass("fa-check").removeClass("fa-question");
					$(this).css('color', 'green');
					$(inputValId).replaceWith("<input id ='input"+id+"' value='true' type='hidden'>")
				}
			}
		)},
	};
});