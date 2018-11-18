layui.use(['table'], function(){ 
    var  table = layui.table;
  
    table.render({
      elem: '#main-table-page'
      ,url: dataUrl
      ,cols: [[
         {field:'id',title: 'ID'}
		,{field:'title',  title: '标题'}
        ,{field:'IssueNo',  title: '期号'}
        ,{field:'LotteryOpen',   title: '开奖结果'}
        ,{field:'plan',   title: '计划'}
		,{field:'Multiple',   title: '倍数'}
        ,{field:'result',  title: '结果'}
		,{width:178, align:'center', fixed: 'right', toolbar: '#main-table-operate'}
      ]]
      ,page: true
	  ,toolbar: true
    });
	
	//监听工具条
    table.on('tool(main-table-page)', function(obj){
      var data = obj.data;
      if(obj.event === 'detail'){
        layer.msg('ID：'+ data.id + ' 的查看操作');
      } else if(obj.event === 'del'){
        layer.confirm('真的删除行么', function(index){
		  $.get(delUrl+data.id,function(){
			  location.reload();
		  });	
          obj.del();
          layer.close(index);
        });
      } else if(obj.event === 'edit'){
        location.href=editUrl+data.id;
      }
    });

  });
