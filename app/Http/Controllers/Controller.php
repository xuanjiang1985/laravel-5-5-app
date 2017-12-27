<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Traits\APIResponseTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
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
