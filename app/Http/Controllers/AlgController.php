<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Events\WechatLoginedEven;
use Validator;
use App\Jobs\StoreLogJob;
use TeamTNT\TNTSearch\TNTSearch;
use App\Services\LogService;

class AlgController extends Controller
{

    public function index2(Request $request)
    {
    	$config = [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST'),
            'database'  => env('DB_DATABASE'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD'),
            'storage'   => storage_path()
        ];

        $tnt = new TNTSearch;

        $tnt->loadConfig($config);
        $tnt->selectIndex("article.index");

        //this will return all documents that have romeo in it but not juliet
        $res = $tnt->search("tnt laravel");
        dd($res);

    }

    public function index()
    {
        $logService = new LogService();
        return $logService->getCode();
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
