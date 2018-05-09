<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Events\WechatLoginedEven;
use Validator;
use App\Jobs\StoreLogJob;

class AlgController extends Controller
{
    public function index(Request $request)
    {
    	$count = DB::table('hash_table_count')->first()->sum;
        StoreLogJob::dispatch($request->ip().' 访问了网站');
        return '已计算了哈希'.$count.'次。';
    }

    public function broadcast()
    {
    	return view('index');
    }

    /**
     * 存二维码网址
     * @param Request $request 
     * @return view
     */
    public function storeQr(Request $request)
    {

    	$this->validate($request, [
    					'site' => 'required|min:10'
    				]);
    	DB::table('sites')->insert([
    		'site' => $request->input('site'),
    		'created_at' => time()
    	]);
    	return redirect('/qr');
    }

    /**
     * 获取二维码网址
     * @return view
     */
    public function getQr()
    {
    	$sites = DB::table('sites')->orderBy('sort')->get();
    	return view('qr', ['sites' => $sites]);
    }

    /**
     * 二维码网址排序
     * @param Request $request 
     * @param  int $id 主键
     * @return json
     */
    public function sortQr(Request $request, $id)
    {
    	$this->validate($request, [
    					'value' => 'required|integer'
    				]);
    	DB::table('sites')->where('id', $id)->update(['sort' => $request->input('value')]);
    	return json_encode(['code' => 0]);
    }
}
