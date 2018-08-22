<?php
namespace app\index\logic;
use think\facade\Cache;
use think\facade\Log;

abstract class utils {

	static function getRoom($id)
	{
		$info = Cache::handler()->hgetall(self::ROOM_ID($id));

		if (empty($info))
		{
			return $info;
		}

		self::cvtToInt($info, 'uid');
		self::cvtToInt($info, 'roomid');
		self::cvtToInt($info, 'vid');
		self::cvtToInt($info, 'num');

		if (!isset($info['num']))
		{
			$info['num'] = 4;
		}

		$num = $info['num'];
		for ($i = 1; $i <= $num; ++$i)
		{
			$mkey = self::MEMBER_KEY($i);
			if (isset($info[$mkey]))
			{
				$info[$mkey] = (int)$info[$mkey];
			}
		}

		return $info;
	}

	static function cvtToInt($arr, $key)
	{
		if (isset($arr[$key])){
			$arr[$key] = (int)($arr[$key]);
		}
	}

	static function getUser($id)
	{
		
		$user = Cache::handler()->hgetall(self::UID($id));
		if (isset($user['uid'])){
			$user['uid'] = (int)($user['uid']);
		}
		if (isset($user['gold'])){
			$user['gold'] = (int)($user['gold']);
		}
		if (isset($user['inroomid'])){
			$user['inroomid'] = (int)($user['inroomid']);
		}
		
		return $user;
	}

	static function MEMBER_KEY($i)
	{
		return 'member_'.$i;
	}

	static function ROOM_ID($id)
	{
		return $id.":roomid";
	}

	static function SID($id)
	{
		return $id.":sid";
	}
	static function UID($id)
	{
		return $id.":uid";
	}

	static function ARR_VAL($arr, $key, $default = 0)
	{
		return array_key_exists($key, $arr) ? $arr[$key] : $default;
	}

	static function GUID($namespace = '') {   
		static $guid = '';
		$uid = uniqid("", true);
		$data = $namespace;
		$data .= self::ARR_VAL($_SERVER, 'REQUEST_TIME');
		$data .= self::ARR_VAL($_SERVER, 'HTTP_USER_AGENT');
		$data .= self::ARR_VAL($_SERVER, 'LOCAL_ADDR');
		$data .= self::ARR_VAL($_SERVER, 'LOCAL_PORT');
		$data .= self::ARR_VAL($_SERVER, 'REMOTE_ADDR');
		$data .= self::ARR_VAL($_SERVER, 'REMOTE_PORT');
		$hash = (hash('ripemd128', $uid . $guid . md5($data)));

		// $guid = '{' .  
		// substr($hash, 0, 8) . 
		// '-' .
		// substr($hash, 8, 4) .
		// '-' .
		// substr($hash, 12, 4) .
		// '-' .
		// substr($hash, 16, 4) .
		// '-' .
		// substr($hash, 20, 12) .
		// '}';
		// return $guid;
		return $hash;
	}
	
}