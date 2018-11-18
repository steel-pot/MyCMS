
layui.use(['laydate'], function(){
	  var laydate = layui.laydate; 
	  $('.laydate-normal').each(function(){
		  	laydate.render({
		      elem: this
		    });	  	
	  });

 
});
