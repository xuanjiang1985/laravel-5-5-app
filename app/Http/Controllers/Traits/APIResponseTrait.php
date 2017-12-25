<?php

namespace App\Http\Controllers\Traits;

trait APIResponseTrait
{
	/**
     * 成功返回
     * @param array $data
     * @param int $code
     * @return array
     */
    public function success($data = [],$code = 0,$msg = '操作成功'){
        return [
            'data' => $data,
            'msg' => is_array($data) || is_numeric($data) ? $msg : $data,
            'code' => $code
        ];
    }

    /**
     * 失败返回
     * @param $msg
     * @param $code
     * @return array
     */
    public function error($msg,$code = 1){
        return [
            'data' => [],
            'msg' => $msg,
            'code' => $code
        ];
    }
}