<?php
namespace app\index\logic;

abstract class Pack {

	static function output($cmd, $data = null)
	{
		// if not data then 
		// 	data = cmd 
		// 	cmd = nil
		// end

		if ($data === null)
		{
			$data = $cmd;
			$cmd = null;
		}


	// local ret = {
	// 		ret = 0,
 //            error = 0,
 //            code = 0,
 //            data = data
 //    }

		$ret = array();
		$ret['ret'] = 0;
		$ret['error'] = 0;
		$ret['code'] = 0;
		$ret['data'] = $data;

		// if cmd then ret.cmd = cmd end
		if ($cmd)
		{
			$ret['cmd'] = $cmd;
		}

		return json($ret);
	}

	
	static function output_fail($cmd, $data = null)
	{
		if ($data === null)
		{
			$data = $cmd;
			$cmd = null;
		}
			// ret = data,
			// code = data,
			// desc = ECODE.ErrDesc(data),
   //          error = data,
   //          data = ECODE.ErrDesc(data)
		$ret = array();
		$ret['ret'] = $data;
		$ret['code'] = $data;
		$ret['error'] = $data;
		$ret['desc'] = LogicCode::Desc($data);	
		$ret['data'] = LogicCode::Desc($data);	

		// if cmd then ret.cmd = cmd end
		if ($cmd)
		{
			$ret['cmd'] = $cmd;
		}

		return json($ret);
	}
}