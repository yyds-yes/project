<?php
/**
 * @file string_class.php
 * @brief 字符串处理库
 * @author localuser1
 * @version 0.6
 */
namespace App\util;
/**
 * @class IString
 * @brief 字符串处理
 */
class IString
{
	/**
	 * @brief 字符串截取
	 * @param string $str 被截取的字符串
	 * @param int $length 截取的长度 值: 0:不对字符串进行截取(默认)
	 * @param bool $append 是否追加省略号 值: true:追加; false:不追加;
	 * @param string $charset $str的编码格式 值: utf8:默认;
	 * @return string 截取后的字符串
	 */
	public static function substr($str, $length = 0, $append = true, $isUTF8=true)
	{
		$byte   = 0;
		$amount = 0;
		$str    = trim($str);
		$length = intval($length);

		//获取字符串总字节数
		$strlength = strlen($str);

		//无截取个数 或 总字节数小于截取个数
		if($length==0 || $strlength <= $length)
		{
			return $str;
		}

		//utf8编码
		if($isUTF8 == true)
		{
			while($byte < $strlength)
			{
				if(ord($str{$byte}) >= 224)
				{
					$byte += 3;
					$amount++;
				}
				else if(ord($str{$byte}) >= 192)
				{
					$byte += 2;
					$amount++;
				}
				else
				{
					$byte += 1;
					$amount++;
				}

				if($amount >= $length)
				{
					$resultStr = substr($str, 0, $byte);
					break;
				}
			}
		}

		//非utf8编码
		else
		{
			while($byte < $strlength)
			{
				if(ord($str{$byte}) > 160)
				{
					$byte += 2;
					$amount++;
				}
				else
				{
					$byte++;
					$amount++;
				}

				if($amount >= $length)
				{
					$resultStr = substr($str, 0, $byte);
					break;
				}
			}
		}

		//实际字符个数小于要截取的字符个数
		if($amount < $length)
		{
			return $str;
		}

		//追加省略号
		if($append)
		{
			$resultStr .= '...';
		}
		return $resultStr;
	}
	/**
	 * @brief 检测编码是否为utf-8格式
	 * @param string $word 被检测的字符串
	 * @return bool 检测结果 值: true:是utf8编码格式; false:不是utf8编码格式;
	 */
	public static function isUTF8($word)
	{
		if(extension_loaded('mbstring'))
		{
			return mb_check_encoding($word,'UTF-8');
		}
		else
		{
			if(preg_match("/^([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){1}$/",$word) == true || preg_match("/([".chr(228)."-".chr(233)."]{1}[".chr(128)."-".chr(191)."]{1}[".chr(128)."-".chr(191)."]{1}){2,}/",$word) == true)
			{
				return true;
			}
			return false;
		}
	}

	/**
	 * @brief 转换字符串编码
	 * @param string $str 被转换的字符串
	 * @param string $toEncode 要转换的字符串编码
	 * @param string $fromEncode 源字符串编码
	 * @return string 转换结果
	 */
	public static function converEncode($str,$toEncode,$fromEncode)
	{
		if(extension_loaded('mbstring'))
		{
			return mb_convert_encoding($str,$toEncode,$fromEncode);
		}
		else
		{
			return iconv($fromEncode,$toEncode."//IGNORE",$str);
		}
	}

	/**
	 * @brief 获取字符个数
	 * @param string 被计算个数的字符串
	 * @return int 字符个数
	 */
	public static function getStrLen($str)
	{
		$byte   = 0;
		$amount = 0;
		$str    = trim($str);

		//获取字符串总字节数
		$strlength = strlen($str);

		//检测是否为utf8编码
		$isUTF8=self::isUTF8($str);

		//utf8编码
		if($isUTF8 == true)
		{
			while($byte < $strlength)
			{
				if(ord($str{$byte}) >= 224)
				{
					$byte += 3;
					$amount++;
				}
				else if(ord($str{$byte}) >= 192)
				{
					$byte += 2;
					$amount++;
				}
				else
				{
					$byte += 1;
					$amount++;
				}
			}
		}

		//非utf8编码
		else
		{
			while($byte < $strlength)
			{
				if(ord($str{$byte}) > 160)
				{
					$byte += 2;
					$amount++;
				}
				else
				{
					$byte++;
					$amount++;
				}
			}
		}
		return $amount;
	}

	/**
	 * @brief 清理字符串的bom头
	 * @param string  要清理的字符串
	 * @return string 清理后的字符串
	 */
	public static function clearBom($str)
	{
		if($str && ord($str[0]) == 239 && ord($str[1]) == 187 && ord($str[2]) == 191)
		{
			$str = substr($str,3);
		}
		return $str;
	}
}