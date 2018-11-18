$("input[type=radio][name=plan][value="+row.plan+"]").attr("checked",'checked').next().trigger('click');
 
function updateData()
{
$.post("",$("form").serialize(),function(rs){
	window.location.href=document.referrer;
});
}