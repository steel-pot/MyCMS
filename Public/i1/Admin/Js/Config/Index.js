layui.use(['upload'],function(){
	var upload = layui.upload;
	 upload.render({
      elem: '.upload'
	  ,url:uploadUrl
      ,before: function(){
         //layer.tips(uploadUrl, this.item, {tips: 1});
			layer.load(0, {shade: false});
      }
      ,done: function(res, index, upload){
		layer.closeAll();
         var item = this.item;
		if(res.result=='1')
		{			
			$("[name="+item.attr('data_id')+"]").val(res.url);
		}
		layer.alert(res.info);
       
      }
    })

});