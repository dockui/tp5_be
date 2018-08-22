<?php
namespace app\index\logic;
use think\facade\Cache;
use think\facade\Log;

require("cmd.php");
require("pack.php");

use app\index\model\User;


class LogicServer
{
	function __construct($name) {
        $this->name=$name;
    }

	function process()
    {
    	if (!request()->has('params','get')){
    		return Pack::output_fail(LogicCode::ERR_PARAMS);
    	}

        $params = request()->param('params');
        $params = json_decode($params, true);
        
        if (null === $params){
        	return Pack::output_fail(LogicCode::ERR_PARAMS); 
        }

		$action = $params['action'];

// call_user_func(__NAMESPACE__.'\incrementa', $a);
		$bexist = method_exists($this, $action);
		if (!$bexist)
		{
			return Pack::output_fail(LogicCode::ERR_PARAMS);
		}
		return call_user_func_array(array($this, $action), array($params));
    }

    function login($params)
    {
    	// return LogicCmd::ReqLogin;
    	// return LogicCode::Desc(1000);
    	$cmd = isset($params['cmd']) ? $params['cmd'] : 0;
    	$sid = isset($params['sid']) ? $params['sid'] : 0;
    	$uid = (int)Cache::handler()->get(utils::SID($sid));
    	do{
			if (!$uid)
	        {
	        	if ($cmd){
	        		Log::error("login not find user");
	        		break;
	        	}

	        	$user           = new User;
				$user->name     = 'tmpname';
				$user->save();

				$uid = (int)$user->id;
				$sid = utils::GUID();

				Cache::handler()->set(utils::SID($sid), $uid);
				Cache::handler()->hmset(utils::UID($uid), 
					array(
						'uid' => $uid,
						'sid' =>$sid,
						'gold' => 200,
						'name' => $user->name
						));
	        }
	        $userCache = utils::getUser($uid);
	        if (!array_key_exists('sid', $userCache) or $userCache['sid'] !== $sid)
	        {
	        	Log::error("login sid error");
	        	break;
	        }
	        // $inroomid = array_key_exists('inroomid', $userCache) ? $userCache['inroomid'] : 0;
	        $inroomid = utils::ARR_VAL($userCache, 'inroomid');
	        if ($inroomid)
	        {
	        	$inroom_info = utils::getRoom($inroomid);
	        	if (empty($inroom_info))
	        	{
	        		Cache::handler()->hdel(utils::UID($uid), 'inroomid');
	        		unset($userCache['inroomid']);
	        	}

	        	if ($cmd and !empty($inroom_info)){
	        		$userCache['inroom_info'] = $inroom_info;
	        	}
	        }

    	    return Pack::output(LogicCmd::RES_LOGIN, $userCache);

    	} while(false);
        
    	return Pack::output_fail(LogicCmd::RES_LOGIN, LogicCode::ERR_VERIFY_FAILURE);
    	// return json($params);
    }

	function _isLogined($sid)
    {
        $uid = (int)Cache::handler()->get(utils::SID($sid));
        if (!$uid)
        {
        	return false;
        }

        $sidCurr = Cache::handler()->hget(utils::UID($uid), 'sid');
        if ($sidCurr !== $sid)
        {
        	return false;
        }
        return true;
    }

    function _allocNewRoomId()
    {
    	$roomid = 626121;
    	do
    	{
    		++$roomid;
    		if (!Cache::handler()->exists(utils::ROOM_ID($roomid)))
    		{
    			return $roomid;
    		}
    	}while(true);
    }

    function CreateRoom($params)
    {
    	$cmd = (int)utils::ARR_VAL($params, 'cmd');
    	$sid = utils::ARR_VAL($params, 'sid');
    	$vid = (int)utils::ARR_VAL($params, 'vid');
    	$num = (int)utils::ARR_VAL($params, 'num');
    	if (!$num)
    	{
    		$num = (int)utils::ARR_VAL($params, 'player');
    	}

    	if (!self::_isLogined($sid))
    	{
    		return Pack::output_fail(LogicCode::ERR_VERIFY_FAILURE);
    	}

    	$uid = (int)Cache::handler()->get(utils::SID($sid));
    	$userCache = utils::getUser($uid);

    	$roomid_cur = (int)utils::ARR_VAL($userCache, 'inroomid');
    	if ($roomid_cur)
    	{
    		$inroom_info_cur = utils::getRoom($roomid_cur);
	    	if (!empty($inroom_info_cur))
	    	{
	    		return Pack::output_fail(LogicCode::ERR_ALREADY_IN_ROOM);
	    	}
    	}

    	$gold_need = 2;
    	$gold_curr = utils::ARR_VAL($userCache, 'gold');
    	if (!$gold_curr || $gold_curr < $gold_need)
    	{
    		return Pack::output_fail(LogicCode::ERR_GOLD_NOT_ENOUGH);
    	}
    	$gold_curr -= $gold_need;
    	$userCache['gold'] = $gold_curr;

    	Cache::handler()->hset(utils::UID($uid), 'gold', $gold_curr);


    	$roomid = self::_allocNewRoomId();

    	$roominfo = array(
    		'uid' => $uid,
    		'vid' => $vid,
    		'num' => $num,
    		'roomid' => $roomid
    		);

    	Cache::handler()->hmset(utils::ROOM_ID($roomid), $roominfo);
    	Cache::handler()->expire(utils::ROOM_ID($roomid), 24*3600);

    	$params = array_merge($params, array('roomid' => $roomid));
    	return self::join_room($params);
    	// return Pack::output($roominfo);
    }

    function join_room($params)
    {
    	$cmd = (int)utils::ARR_VAL($params, 'cmd');
    	$sid = utils::ARR_VAL($params, 'sid');

    	$roomid = (int)utils::ARR_VAL($params, 'roomid');

    	if (!$roomid)
		{
			return Pack::output_fail(LogicCode::ERR_PARAMS);	
		}

    	if (!self::_isLogined($sid))
    	{
    		return Pack::output_fail(LogicCode::ERR_VERIFY_FAILURE);
    	}

    	$uid = (int)Cache::handler()->get(utils::SID($sid));
    	$userCache = utils::getUser($uid);

    	$roomid_cur = (int)utils::ARR_VAL($userCache, 'inroomid');
    	if ($roomid_cur && $roomid_cur !== $roomid)
    	{
    		$inroom_info_cur = utils::getRoom($roomid_cur);
	    	if (!empty($inroom_info_cur))
	    	{
	    		return Pack::output_fail(LogicCode::ERR_ALREADY_IN_ROOM);
	    	}
    	}

    	$inroom_info = $roomid ? utils::getRoom($roomid) : 0;
    	if (empty($inroom_info))
    	{
    		Cache::handler()->hdel(utils::UID($uid), 'inroomid');
    		unset($userCache['inroomid']);

    		return Pack::output_fail(LogicCode::ERR_NOT_EXIST);
    	}

    	$num = (int)utils::ARR_VAL($inroom_info, 'num');

    	$find_index = 0;
    	$null_index = 0;
    	for ($i = 1; $i <= $num; ++$i)
		{
			$mkey = utils::MEMBER_KEY($i);
			$uid_tmp = (int)utils::ARR_VAL($inroom_info, $mkey);
			if (!$null_index && !$uid_tmp)
			{
				$null_index = $i;
			}

			if ($uid === $uid_tmp)
			{
				$find_index = $i;
				break;
			}
		}

		if (!$find_index)
		{
			if (!$null_index)
			{
				return Pack::output_fail(LogicCode::ERR_ROOM_FULL);
			}
			
			$memKey = utils::MEMBER_KEY($null_index);
			$inroom_info[$memKey] = $uid;

			Cache::handler()->hset(utils::ROOM_ID($roomid), $memKey, $uid);
			Cache::handler()->expire(utils::ROOM_ID($roomid), 24 * 3600);
		}

		Cache::handler()->hset(utils::UID($uid), 'inroomid', $roomid);
		return Pack::output($inroom_info);
	}

	function exit_room($params)
    {
    	$cmd = (int)utils::ARR_VAL($params, 'cmd');
    	$sid = utils::ARR_VAL($params, 'sid');

    	$roomid = (int)utils::ARR_VAL($params, 'roomid');

  //   	if (!$roomid)
		// {
		// 	return Pack::output_fail(LogicCode::ERR_PARAMS);	
		// }

    	if (!self::_isLogined($sid))
    	{
    		return Pack::output_fail(LogicCode::ERR_VERIFY_FAILURE);
    	}

    	$uid = (int)Cache::handler()->get(utils::SID($sid));
    	$userCache = utils::getUser($uid);

		$roomid_cur = (int)utils::ARR_VAL($userCache, 'inroomid');

		if (!$roomid)
		{
			$roomid = $roomid_cur;
		}

		$inroom_info = $roomid ? utils::getRoom($roomid) : 0;
    	if (empty($inroom_info))
    	{
    		Cache::handler()->hdel(utils::UID($uid), 'inroomid');
    		unset($userCache['inroomid']);

    		return Pack::output_fail(LogicCode::ERR_NOT_EXIST);
    	}

		$num = (int)utils::ARR_VAL($inroom_info, 'num');
    	$find_index = 0;
    	$null_index = 0;
    	for ($i = 1; $i <= $num; ++$i)
		{
			$mkey = utils::MEMBER_KEY($i);
			$uid_tmp = (int)utils::ARR_VAL($inroom_info, $mkey);
			if (!$null_index && !$uid_tmp)
			{
				$null_index = $i;
			}

			if ($uid === $uid_tmp)
			{
				$find_index = $i;
				break;
			}
		}

		if ($find_index)
		{
			$memKey = utils::MEMBER_KEY($find_index);
			unset($inroom_info[$memKey]);

			Cache::handler()->hdel(utils::ROOM_ID($roomid), $memKey);
			Cache::handler()->expire(utils::ROOM_ID($roomid), 24 * 3600);
		}

		if ($roomid_cur)
		{
			Cache::handler()->hdel(utils::UID($uid), 'inroomid');
		}

		return Pack::output($inroom_info);
    }

	function remove_room($params)
    {
    	$cmd = (int)utils::ARR_VAL($params, 'cmd');
    	$sid = utils::ARR_VAL($params, 'sid');

    	$roomid = (int)utils::ARR_VAL($params, 'roomid');

    	if (!$roomid)
		{
			return Pack::output_fail(LogicCode::ERR_PARAMS);	
		}

    	if (!self::_isLogined($sid))
    	{
    		return Pack::output_fail(LogicCode::ERR_VERIFY_FAILURE);
    	}

    	$uid = (int)Cache::handler()->get(utils::SID($sid));
    	$userCache = utils::getUser($uid);

		$inroom_info = $roomid ? utils::getRoom($roomid) : 0;
    	if (empty($inroom_info))
    	{
    		return Pack::output_fail(LogicCode::ERR_NOT_EXIST);
    	}

    	Cache::handler()->del(utils::ROOM_ID($roomid));

    	return Pack::output($inroom_info);
	}
}