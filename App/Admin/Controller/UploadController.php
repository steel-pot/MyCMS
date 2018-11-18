<?php
namespace Admin\Controller; 
use Fw\Model;
class UploadController extends BaseController
{
	public function Index()
	{
		$file=$_FILES['file'];
		if ($file["error"] > 0)
		{			
			$this->returnData(0,'上传出错，code:'.$_FILES["file"]["error"]);
		}
		$ext=explode('.',$file['name']); 
		$ext=$ext[count($ext)-1];
		$ext=strtolower($ext);
		if(!in_array($ext,array('gif','jpg','png')))
		{
			$this->returnData(0,'上传出错，只能上传gif,jpg,png');
		}
		if($file['size']>2*1024*1024)
		{
			$this->returnData(0,'上传出错，文件大小不能大于2M');
		}
		
		$dir=MAIN_DIR.'/Public';
		$path='/i/Upload/'.date('Ym');
		$dir=$dir.$path;
		if(!file_exists($dir))
		{
			mkdir($dir,0777,true);	
		}
		$filename=md5($file['name'].time().mt_rand().mt_rand()).'.'.$ext;
		$movePath=$dir.'/'.$filename;
		$url=$path.'/'.$filename;
		if(move_uploaded_file($file['tmp_name'],$movePath))
		{
			
			$this->returnData(1,'上传成功,请点击保存按钮来保存配置',$url);
		}else{
			$this->returnData(0,'上传出错，移动文件时失败');
		}
		 
	}
	private function returnData($result,$info,$url='')
	{
		 header('Content-Type:application/json; charset=utf-8'); 
		 echo json_encode(array('result'=>$result,'info'=>$info,'url'=>$url));
		 exit;
	}
}