<?php
	class Helper{

		private static $sington;
		public static function getSington()
		{
			if(empty(Helper::$sington))Helper::$sington=new Helper();
			return Helper::$sington;
		}
		
	}
?>