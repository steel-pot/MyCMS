
var disp=1;
var L1="<a href='"+ad_left_link+"' target=_blank style=display:inline-block;height:295px;><img src='"+ad_left_img+"' WIDTH=190 HEIGHT=380 /></a><p></p>";
var L2="<img src='/i/img/close.gif' onClick='javascript:window.hide()' width='190' height='14' border='0' vspace='3' alt='¹Ø±Õ'>";

var R1="<a href='"+ad_right_link+"' target=_blank style=display:inline-block;height:295px;><img src='"+ad_right_img+"' WIDTH=190 HEIGHT=380 /></a><p></p>";
var R2="<img src='/i/img/close.gif' onClick='javascript:window.hide()' width='190' height='14' border='0' vspace='3' alt='¹Ø±Õ'>";

if(disp==1)
{
lastScrollY=0;
function heartBeat(){ 
var diffY;
if (document.documentElement && document.documentElement.scrollTop)
diffY = document.documentElement.scrollTop;
else if (document.body)
diffY = document.body.scrollTop
else
{/*Netscape stuff*/}
percent=.1*(diffY-lastScrollY); 
if(percent>0)percent=Math.ceil(percent); 
else percent=Math.floor(percent); 
document.getElementById("lovexin12").style.top=parseInt(document.getElementById("lovexin12").style.top)+percent+"px";
document.getElementById("lovexin14").style.top=parseInt(document.getElementById("lovexin14").style.top)+percent+"px";
lastScrollY=lastScrollY+percent; 
}
suspendcode12="<DIV id=\"lovexin12\" style='left:10px;POSITION:absolute;TOP:25px;width:140px;'>" + L1 + L2 + "</div>"
suspendcode14="<DIV id=\"lovexin14\" style='right:10px;POSITION:absolute;TOP:25px;width:180px;'>" + R1 + R2 + "</div>"
document.write(suspendcode12); 
document.write(suspendcode14); 
window.setInterval("heartBeat()",1);

function hide()  
{
lovexin12.style.visibility="hidden"; 
lovexin14.style.visibility="hidden";
}
}