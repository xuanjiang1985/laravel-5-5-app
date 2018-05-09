<?php

namespace App\Services;

use Log;

class LogService extends BaseService
{
	public function storeLog($str = '')
	{
		try {
			Log::info($str);
			return true;

		} catch (\Exception $e) {
			return $this->sendErrorMessage(false, 1001, '添加失败', $e->getMessage());
		}

	}	
}