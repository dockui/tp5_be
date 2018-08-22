<?php
namespace app\index\logic;


abstract class LogicCmd {

    const RES_LOGIN = 4000;

}

abstract class LogicCode {
	const CODE_SUCCESS = 0;
    const CODE_UNKNOW = 1;
    const ERR_PARAMS = 1000;
    const ERR_NOT_EXIST = 1010;
    const ERR_ROOM_FULL = 1020;
    const ERR_NOT_EXIST_USER = 1030;
    const ERR_VERIFY_FAILURE = 1040;
    const ERR_GOLD_NOT_ENOUGH = 1050;
    const ERR_ALREADY_IN_ROOM = 1060;
    const ERR_NOT_IN_ROOM = 1070;

    private static $DescArray = array(

	    self::CODE_UNKNOW => "unknow",
	    self::ERR_PARAMS => "params error",
	    self::ERR_NOT_EXIST => "not exist",
	    self::ERR_ROOM_FULL => "room is full",
	    self::ERR_NOT_EXIST_USER => "user not exist",
	    self::ERR_VERIFY_FAILURE => "user verify failure",  
	    self::ERR_GOLD_NOT_ENOUGH => "gold not enough",
	    self::ERR_ALREADY_IN_ROOM => "member already in room",
	    self::ERR_NOT_IN_ROOM => "member not in room"
    	);


    static function Desc($code)
    {
    	if ($code == 0){
    		return "success";
    	}

    	return self::$DescArray[$code];
    }
}