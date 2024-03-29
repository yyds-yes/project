<?php
/**
 * @file cookie_class.php
 * @brief 处理 Cookie
 * @author localuser1
 * @version 0.6

 * @update 4.6
 * @note 去除safecode验证
 */
namespace common\util;
/**
 * @class ICookie
 * @brief ICookie的相关操作
 */
use Yii;
use common\util\crypt_class;
class ICookie
{
	//cookie前缀
	private static $pre = 'senseplay_';

	//默认cookie密钥
	private static $defaultKey = 'senseplay';

	//获取配置的前缀
	private static function getPre()
	{
		return self::$pre;
	}

    /**
     * @brief 设置cookie的方法
     * @param string $name 字段名
     * @param string $value 对应的值
     * @param string $time 有效时间天数
     * @param string $path 工作路径
     * @param string $domain 作用域
     */
	public static function set($name,$value='',$time=7,$path='/',$domain=null)
	{
		if($time <= 0)
		{
			$expire = -100;
		}
		else
		{
			$expire = time() + 60 * 60 * 24 * $time;
		}

		self::$pre = self::getPre();
		if(is_array($value) || is_object($value))
		{
			$value = serialize($value);
		}
		$value = ICrypt::encode($value , self::getKey());
		return setCookie(self::$pre.$name,$value,$expire,$path,$domain);
	}

    /**
     * @brief 取得cookie字段值的方法
     * @param string $name 字段名
     * @return mixed 对应的值
     */
	public static function get($name)
	{
		self::$pre = self::getPre();
		if(isset($_COOKIE[self::$pre.$name]))
		{
			$cookie = ICrypt::decode($_COOKIE[self::$pre.$name],self::getKey());
			$tem    = substr($cookie,0,10);
			if(preg_match('/^[Oa]:\d+:.*/',$tem))
			{
				return unserialize($cookie);
			}
			return $cookie;
		}
		return null;
	}

    /**
     * @brief 清除cookie值的方法
     * @param string $name 字段名
     */
	public static function clear($name)
	{
		self::set($name,'',0);
	}

    /**
     * @brief 清除所有的cookie数据
     */
	public static function clearAll()
	{
		self::$pre = self::getPre();
		$preLen = strlen(self::$pre);
		foreach($_COOKIE as $name => $val)
		{
			if(strpos($name,self::$pre) === 0)
			{
				self::clear(substr($name,$preLen));
			}
		}
	}


    /**
     * @brief 取得cookie的安全码
     * @return String cookie的安全码
     */
	private static function cookieId()
	{
		return md5(filemtime(__FILE__));
	}
}