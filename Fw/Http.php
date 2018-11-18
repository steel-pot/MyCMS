<?php
namespace Fw;
class Http
{
	private static $sington;
	public static function getSington()
	{
		if(empty(self::$sington))self::$sington=new Http();
		return self::$sington;
	}

	public function getData($url, $postData = null) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	 
		if ($postData) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		} 
		$curlResponse = curl_exec($ch);
		$curlErrno = curl_errno($ch);
		if ($curlErrno) {
			$curlError = curl_error($ch);
			//throw new Exception($curlError);
		} 
		curl_close($ch); 
		return $curlResponse;
	} 
}