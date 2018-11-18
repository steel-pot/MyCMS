define("component/Dialog",["require","exports","module","component/UIBase","base/base","underscore","base/eventEmitter"],function(e){var t=e("./UIBase"),i=(e("../base/base"),e("underscore")),n=e("base/eventEmitter");return t.extend({options:{id:"",titleText:"默认标题",content:"默认内容",position:{left:360,top:100},isSingle:!1,isModal:!1,hasClose:!0,hideInsteadofClose:!1,className:"dialog",onclose:function(){},zIndex:99999},_type:"dialog",_init:function(){this.options.id=this.id=this.getId(),n.setOwner(this),this.render(),this._bindResizeHander()},_getInnerString:function(){var e=(document,this),t=['<div class="dialog-title">'];return t.push('<div class="dialog-title-text">'),t.push(e.options.titleText),t.push("</div>"),this.options.hasClose&&(t.push('<a href="javascript:void(0);" class="dialog-close">&nbsp;'),t.push("</a>")),t.push("</div>"),t.push('<div class="dialog-content">'),t.push(e._getContentHTML()),t.push("</div>"),t.join("")},_update:function(){var e=this,t=e.getContainer();if(t){var i="#"+e.id+" .dialog-title-text",n=$(i);n.length&&n.html(e.options.titleText);var o="#"+e.id+" .dialog-content",s=$(o);s.length&&s.html(e._getContentHTML()),$.holmes.setPosition(t,e.options.position)}},_setIFrame:function(e,t,i){var n='<iframe class="bgiframe"frameborder="0"tabindex="-1"src="javascript:false;"style="display:block;position:absolute;z-index:-1;filter:Alpha(Opacity=\'0\');top:0px;left:0px;width:'+t+"px;height:"+i+'px;"/>';0===$(e).find("bgiframe").length&&$(e).before(n)},_getContentHTML:function(){var e=this,t=document;if(i.isString(e.options.content))return e.options.content;if(i.isElement(e.options.content)){$(e.options.content).show();var n=t.createElement("div");return $(n).append(e.options.content),$(n).html()}return""},setTitleText:function(e){var t=this;i.isString(e)&&(t.options.titleText=e),t._update()},setContent:function(e){var t=this,n=e;(i.isString(n)||i.isElement(n))&&(t.options.content=n),t._update()},render:function(){var e=document,t=this,i=e.createElement("div");i.id=t.id,i.className="dialog-container "+t.options.className;if(t.options.isModal){var n=t.options.zIndex-1,o=e.createElement("div");if(o.className=t.options.className+" modal",o.id=t.id+"modal",$(o).css({position:"absolute",top:0,left:0,"background-color":"black",width:$(document).width(),height:$(document).height(),opacity:.6,"z-index":n}),$(e.body).append(o),$.browser.msie&&+$.browser.version<7){var s=e.createElement("div");o.appendChild(s),t._setIFrame(o.firstChild,$(window).width(),$(window).height())}}if(i.innerHTML=t._getInnerString(),e.body.appendChild(i),$.browser.msie&&+$.browser.version<7&&t._setIFrame(i.firstChild,i.offsetWidth,i.offsetHeight),$(t.getContainer()).css({position:"absolute","z-index":t.options.zIndex}),t.options.width&&($(t.getContainer()).css({width:t.options.width}),t.options.position.left=$(window).scrollLeft()+($(window).width()-t.options.width)/2,t.options.position.top=Math.max($(window).scrollTop()+($(window).height()-t.getContainer().offsetHeight)/2,0)),$.holmes.setPosition(t.getContainer(),{left:t.options.position.left,top:t.options.position.top}),this.options.hasClose){var r="#"+t.id+" .dialog-close";$(r).on("click",function(){t.options.hideInsteadofClose?t.hide():t.close()})}t.hide()},getContainer:function(){return $("#"+this.id)[0]},setPosition:function(e){var t=this;t.options.position.left=e.left,t.options.position.top=e.top,t._update()},fixPosition:function(){var e=this;e.getContainer()&&(e.options.width&&($(e.getContainer()).css({width:e.options.width}),e.options.position.left=$(window).scrollLeft()+($(window).width()-e.options.width)/2,e.options.position.top=Math.max($(window).scrollTop()+($(window).height()-e.getContainer().offsetHeight)/2,0)),$(e.getContainer()).css({left:e.options.position.left,top:e.options.position.top}));var t=this.getModalContainer();t.css({width:$(document).width(),height:$(document).height()})},show:function(){var e=this;$(e.getContainer()).show(),this.fixPosition(),e.getModalContainer()&&$(e.getModalContainer()).show()},hide:function(){var e=this;$(e.getContainer()).hide(),e.getModalContainer()&&$(e.getModalContainer()).hide()},close:function(){this.options.isSingle?this.hide():(this.getContainer()&&$(this.getContainer()).remove(),this.getModalContainer()&&$(this.getModalContainer()).remove(),$(window).off("resize",this.fixPosition),$(window).off("scroll",this.fixPosition)),n.trigger("onclose")},getModalContainer:function(){return $("#"+this.id+"modal")},_modalResizeHandler:function(){for(var e=$(".modal"),t=0;t<e.length;t++)$(e[t]).css({width:$(window).width(),height:$(window).height()})},_bindResizeHander:function(){var e=this;$(window).resize(function(){e.fixPosition()}),$(window).on("scroll",function(){e.fixPosition()})}})}),define("base/aceTemplate.min",[],function(){function e(e){var t=[];t.push("with(this){"),t.push(e.replace(/[\r\n]+/g,"\n").replace(/^\n+|\s+$/gm,"").replace(/((^\s*[<>!#^&\u0000-\u0008\u007F-\uffff].*$|^.*[<>]\s*$|^(?!\s*(else|do|try|finally)\s*$)[^'":;,\[\]{}()\n\/]+$|^(\s*(([\w-]+\s*=\s*"[^"]*")|([\w-]+\s*=\s*'[^']*')))+\s*$|^\s*([.#][\w-.]+(:\w+)?(\s*|,))*(?!(else|do|while|try|return)\b)[.#]?[\w-.*]+(:\w+)?\s*\{.*$)\s?)+/gm,function(e){return e=['"',e.replace(/&none;/g,"").replace(/["'\\]/g,"\\$&").replace(/\n/g,"\\n").replace(/(!?!?#)\{(.*?)\}/g,function(e,t,i){i=i.replace(/\\n/g,"\n").replace(/\\([\\'"])/g,"$1");var n=/^[a-z$][\w+$]+$/i.test(i)&&!/^(true|false|NaN|null|this)$/.test(i);return['",',n?["typeof ",i,'=="undefined"?"":'].join(""):"","#"==t?"_encode_":"!!#"==t?"_encodeAttr_":"","(",i,'),"'].join("")}),'"'].join("").replace(/^"",|,""$/g,""),e?["_output_.push(",e,");"].join(""):""})),t.push("}");var i=new Function("_output_","_encode_","helper","_encodeAttr_",t.join(""));return i}var t=t||{},i={"#39":"'",quot:'"',lt:"<",gt:">",amp:"&",nbsp:" "},n={"'":"#39",'"':"quot","<":"lt",">":"gt","&":"amp"," ":"nbsp"},o={g:function(e){return"string"!=typeof e?e:document.getElementById(e)},decodeHTML:function(e){return String(e).replace(/&(#39|quot|lt|gt|amp|nbsp);/gi,function(e,t){return i[t]}).replace(/&#u([a-f\d]{4});/gi,function(e,t){return String.fromCharCode(parseInt("0x"+t))}).replace(/&#(\d+);/gi,function(e,t){return String.fromCharCode(+t)})},encodeAttr:function(e){return String(e).replace(/["']/g,function(e){return"&"+n[e]+";"})},encodeHTML:function(e){return String(e).replace(/['"<>& ]/g,function(e){return"&"+n[e]+";"})},elementText:function(e){return e&&e.tagName?/^(input|textarea)$/i.test(e.tagName)?e.value:o.decodeHTML(e.innerHTML):""}},s={},r=!1;return t.format=function(t,i,n){if(!t)return"";var r,a;"object"==typeof t&&t.tagName&&(a=t,t=a.getAttribute("id")),n=n||this,r=s[t],r||(/[^\w-]/.test(t)?r=e(t):(a||(a=o.g(t)),r=this.register(t,a)));var l=[];return r.call(i||"",l,o.encodeHTML,n,o.encodeAttr),l.join("")},t.register=function(t,i){if(!arguments.length&&!r){r=!0;for(var n=document.getElementsByTagName("script"),a=0;a<n.length;a++){var l=n[a];if(/^(text\/template)$/i.test(l.getAttribute("type"))){var t=l.getAttribute("id");t&&arguments.callee.call(this,t,l)}}}if(t)return s[t]?s[t]:("string"!=typeof i&&("undefined"==typeof i&&(i=o.g(t)),i=o.elementText(i)),s[t]=e(i))},t.unregister=function(e){delete s[e]},t.format&&(t._format=t.format,t.format=function(e,i,n){var o=t._format(e,i,n);return String(o).replace(/(.+?)>\s*<(.+?)/g,"$1><$2")}),t}),define("base/loginController",["require","exports","module"],function(e){var t={},i={};i._isValidKey=function(e){return new RegExp('^[^\\x00-\\x20\\x7f\\(\\)<>@,;:\\\\\\"\\[\\]\\?=\\{\\}\\/\\u0080-\\uffff]+$').test(e)},i.setRaw=function(e,t,n){if(i._isValidKey(e)){n=n||{};var o=n.expires;"number"==typeof n.expires&&(o=new Date,o.setTime(o.getTime()+n.expires)),document.cookie=e+"="+t+(n.domain?"; domain="+n.domain:"")+(n.path?"; path="+n.path:"")+(o?"; expires="+o.toGMTString():"")+(n.secure?"; secure":"")}},i.set=function(e,t,n){i.setRaw(e,encodeURIComponent(t),n)},i.getRaw=function(e){if(i._isValidKey(e)){var t=new RegExp("(^| )"+e+"=([^;]*)(;|$)"),n=t.exec(document.cookie);if(n)return n[2]||null}return null},i.get=function(e){var t=i.getRaw(e);return"string"==typeof t?t=decodeURIComponent(t):null};var n={type:0,_safeCheck:[0,1],_ucslLoaded:!1,setConfig:function(e){t=e},init:function(){n.initLoginType(),n.initErrorTip();var e=$("#safe-check");e.length&&(e[0].checked=!1,this.safeCheck(),e.on("click",$.proxy(this.safeCheck,this)));var t=$("#Submit");t.length&&t.on("click",$.proxy(this.submit,this));var i=$("#Client_Submit");i.length&&i.on("click",$.proxy(this.submit,this)),this.initInputErrorStyle()},initInputErrorStyle:function(){$(document).on("click","#LoginInput .form-item-input",function(){$(this).closest(".form-item").removeClass("login-input-error")})},initErrorTip:function(){var e=$("#ErrorTip"),t=$("#ErrorNoSubmit");e.length&&t.hide()},initLoginType:function(){var e=this.getRecord("hm_usertype"),i=t.app_id;if(e=parseInt(e?e:0,10),n.type=e,n.setForgetUrl(e),0===e&&!$("#ErrorTip").length){var o=$("#WebMasterTip");o.length&&o.show()}var s=$("li",$("#LoginType"));if($(s[this.type]).addClass("cur"),s.on("click",$.proxy(this.loginAClick,this)),i&&i==e){var r=this.getRecord("hm_username"),a=$("#UserName");r&&"undefined"!=r&&a.length&&(a[0].value=r)}},loginAClick:function(e){e.preventDefault();var i=$(e.target);i="A"!=i[0].tagName?$("a",i):i;for(var o=$("#LoginType"),s=$("a",o),r=0,a=s.length;a>r;r++)if(i[0]==s[r]){var l=s.eq(r).closest("li");l.addClass("cur");var c=n.type;if(c!=r){var d=$("#ErrorTip"),u=$("#ErrorNoSubmit");d.length&&d.hide(),u.length&&u.hide();var h=$("#UserName");if(h.length)h[0].value="",h.focus();else{var p=$("#Client_Password");p.focus()}var f=$("#Password");f.length&&(f[0].value="");var g=$("#Valicode");g.length&&(g[0].value=""),n.type=r,n.setForgetUrl(r);var m=t.app_id;if(m&&m==r){var v=this.getRecord("hm_username");v&&"undefined"!=v&&h.length&&(h[0].value=v)}}}else{var l=s.eq(r).closest("li");l.removeClass("cur")}var w=$("#WebMasterTip");w.length&&(0==n.type?w.show():w.hide()),$("#safe-check")[0].checked=!1,this.safeCheck()},setForgetUrl:function(e){var i=$("#GetForgetUrl"),n=$("#RegisterUrl");if(i.length)switch(e){case 0:i[0].href=t.uc_forget,n[0].href=t.webMasterRegister;break;case 1:i[0].href=t.shifen_forget,n[0].href=t.customRegister;break;default:i[0].href=t.shifen_forget,n[0].href=t.customRegister}},checkPassword:function(e){return this.isSafeCheck()?!ucsl.verify():""==e},submit:function(e){var t,i=($("#ErrorTip"),$("#ErrorNoSubmit")),o=$(e.target),s=o[0].id;if("Submit"==s){var r=$("#UserName"),a=$("#Password"),l=$("#Valicode"),c=r.length?$.trim(r[0].value):"",d=a.length?$.trim(a[0].value):"",u=l.length?$.trim(l[0].value):"";r.length&&(r[0].value=c),a.length&&(a[0].value=d),l.length&&(l[0].value=u),(""===c||this.checkPassword(d)||""===u)&&(t="用户名，密码或者验证码不能为空"),""===c&&r.closest(".form-item").addClass("login-input-error"),""===d&&a.closest(".form-item").addClass("login-input-error"),""===u&&l.closest(".form-item").addClass("login-input-error")}else{var h=$("#Client_Password"),d=h.length?$.trim(h[0].value):"";0==d.length&&h.length&&(t="密码不能为空")}if(t){var p=$("#WebMasterTip");return p.length&&p.hide(),i.html(t),i.show(),e.preventDefault(),!1}var f=n.type,g=$("#Appid");this.record("hm_usertype",f),this.record("hm_username",c),1==f?this.setAppScope(g,[3,15,33]):this.setAppScope(g,[6,7,12])},record:function(e,t){i.set(e,t,{expires:31536e3,path:"/"})},getRecord:function(e){return i.get(e)},setAppScope:function(e,t){for(var i in t)if(t.hasOwnProperty(i)){var n=t[i],o=$("<input>",{type:"hidden",name:"appscope[]",value:n});$(e).before(o)}},safeCheck:function(){var e=this.isSafeCheck(),t=this;e?(this._ucslLoaded||ucsl.support()&&ucsl.init({fid:"uc-safe-pwd-input",tabout:"Valicode",style:{width:208,height:30},tabindex:2,ready:function(){t._ucslLoaded=!0},fail:function(){}}),$("#uc-safe-pwd-input").removeClass("uc-safe-pwd-input-hidden"),$("#Password").hide()):($("#uc-safe-pwd-input").addClass("uc-safe-pwd-input-hidden"),$("#Password").show())},changeSafeCheck:function(){var e=this.getSafeCheck();e[this.type]=$("#safe-check")[0].checked?1:0,this.record("hm_safecheck",e.join()),this.safeCheck()},getSafeCheck:function(){var e=this.getRecord("hm_safecheck");return e=null==e?[0,1]:e.split(",")},isSafeCheck:function(){return!1}};return n}),require(["jquery","domReady","component/Dialog","component/Tabs","base/aceTemplate.min","base/loginController","base/util"],function(e,t,i,n,o,s,r){e(document).ready(function(){var t=e("#main-nav .main-content>ul>li"),i=e("#sub-nav"),o=0,s=function(n){o=n;var s=t.eq(n),r=s.find(".sub-nav-title"),a=["<ul>"];r.each(function(t,i){a.push((0===t?'<li class="selected">':"<li>")+'<a href="#'+e(i).attr("id")+'">'+e(i).html()+"</a></li>")}),a.push("</ul>"),i.empty().html(a.join(""))};new n({containerId:"main-nav",selectedIndex:0,onchange:s}),i.on("click","a",function(t){t.preventDefault(),i.find("li").removeClass("selected"),e(this).closest("li").addClass("selected"),e("html,body").animate({scrollTop:e(this.hash).offset().top},300)});var r,a=i.offset().top,l=function(){var n=this;window.clearTimeout(r),r=window.setTimeout(function(){var s=e(n).scrollTop(),l=t.eq(o).find(".sub-nav-title"),c=l.map(function(){return e(this).offset().top<=s?e(this):void 0}),d=c.length-1<0?0:c.length-1;i.find("li").removeClass("selected").eq(d).addClass("selected"),i.css(s>=a?{position:"fixed"}:{position:"static"}),window.clearTimeout(r)},60)};e(window).on("scroll",l)})}),define("products",["jquery","domReady","component/Dialog","component/Tabs","base/aceTemplate.min","base/loginController","base/util"],function(){});