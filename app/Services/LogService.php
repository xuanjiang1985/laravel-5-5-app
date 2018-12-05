<?php

namespace App\Services;

class LogService extends BaseService
{
	private $code = 123123;
	const STATE = 3;
	const RELATION = [
        '爸爸',
        '妈妈',
        '爷爷',
    ];
	static public function storeLog($str = '')
	{
		try {
			info($str);
			return true;

		} catch (\Exception $e) {
			return 0;
		}

	}

	public function getCode()
	{
		return SELF::STATE;
	}
	
}