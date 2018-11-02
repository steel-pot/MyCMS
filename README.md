# MyCMS

使用命名空间，
核心内容 在Fw空间下
Fw\Controller

模块中的命名空间直接用模块名就行
Admin\Lib\test

需要在index.php中配置下可访问的模块名称$moduleList

I表示资源目录在url中的绝对路径

占用了几个字段 m  c   a     $_GET中不要使用这几个字段

另占用了几个变量名  global $m $c $a $rewrite $moduleList



Router.php是路由配置，不需要可以删除

如果index.php不放在public目录，注意目录安全问题

Libs 里面放自定义插件，需果是项目中用到的插件，可以在APP中的项目下建目录来放

Helper.php中有一些助手函数

模仿thinkphp做了个request()助手函数，实际是Fw\Request类，可以在里面做一些常用的获取运行变量的方法

request()->get('key');

request()->post('key');

request()->arg('key');//在get和post中获取

request()->ip();

request()->isPost();

request()->isAjax();


 composer 支持还没添加