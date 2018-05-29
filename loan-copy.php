<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
	    <meta name="keywords" content="">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<title>产品详情</title>
	    <meta name="renderer" content="webkit">
	    <meta http-equiv="Cache-Control" content="no-siteapp">
	    <meta name="mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-capable" content="yes">
	    <meta name="apple-mobile-web-app-status-bar-style" content="black">
	    <meta name="format-detection" content="telephone=no">
	    <link rel="stylesheet" href="{{ static_asset('static/wechat/css/normalize.css') }}">
	    <link rel="stylesheet" href="{{ static_asset('static/wechat/css/layer.css') }}"/>
	    <link rel="stylesheet" href="{{ static_asset('static/loan/css/layout.css') }}">
	</head>
	<body style="background: #F2F3F4;">
		<header>
			<ul>
				<li>
					<i>
                        <img src="{{ oss_get($product->image ?? '') }}"/>
                    </i>
				</li>
				<li>
					<p>{{ $product->des or ''}}</p>
					<p>产品月利率：{{ $product->wx_month_interest or '' }}</p>
				</li>
			</ul>
			<div class="clear"></div>
			<span></span>
		</header>
		<aside>
			<ul id="amount-limit">
				<li>
					<p>借款金额</p>
					<p><span id="amount">100000</span>元</p>
				</li>
				<li>
					<p>借款期限</p>
					<p><span id="time_limit">1</span>个月</p>
				</li>
			</ul>
		</aside>
		
		<section>
			<ul>
				<li>
					<p>姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名：</p>
					<input type="text" name="name" id="user_name" placeholder="请输入姓名" value="{{ $real_name['real_name'] or '' }}" {{ (isset($real_name['real_name']) && '' != $real_name['real_name']) ? 'readonly' : '' }}/>
				</li>
				<li>
					<p>手机号码：</p>
					<input type="tel" name="mobile" value="{{ $user['mobile'] }}" id="mobile" readonly/>
				</li>
				<li>
					<p>验&nbsp; 证&nbsp; 码：</p>
					<div class="section-code">
						<input type="tel" name="verify_code" value="" id="code" placeholder="请输入验证码" maxlength="4"/>
						<span id="code_btn">发送验证码</span>
					</div>
				</li>
				<li>
					<p>贷款目的：</p>
					<input type="text" name="purpose" value="" id="aim"/>
				</li>
				
			</ul>
			<p>点击提交即默认为同意<a href="{{static_asset('/loan/pact')}}">《贷款咨询与服务协议》</a></p>
			
			<footer>
				<a href="javascript:void(0);">立即提交</a>
			</footer>
		</section>
		
		<script type="text/javascript" src="{{ static_asset('static/wechat/js/flexible.js') }}"></script>
	    <script type="text/javascript" src="{{ static_asset('static/wechat/js/jquery-1.11.3.js') }}"></script>
	    <script type="text/javascript" src="{{ static_asset('static/wechat/js/layer.js') }}"></script>
	    
	    <script type="text/javascript">
	    	
    	$(function(){
            var userProductQuota = {{ $user_product_quota }};
            var csrf_token = '{{ csrf_token() }}';
	    	//手机验证的函数
			function validatePhone(ph){
				var pattern=new RegExp(/^1[3|4|5|7|8]\d{9}$/);
				return pattern.test(ph);
			}
			//验证码的函数
			function isCode(code){
				var pattern=new RegExp(/^[0-9]{4}$/);
				return pattern.test(code);
			}
			
			$("footer a").click(function(){
				var name = $('#user_name').val();
		    	var mobile = $('#mobile').val();
                var code = $('#code').val();
                var purpose = $('#aim').val();
                var amount = $('#amount').text();
                var time_limit = $('#time_limit').text();
                
                if(amount === '' || amount === 0) {
                    layer.open({
					    content: '请输入贷款金额'
					    ,skin: 'msg'
					    ,time: 2 //2秒后自动关闭
                      });
                    return;
                }

                if(amount > userProductQuota) {
                    layer.open({
					    content: '贷款金额不能超出' + userProductQuota
					    ,skin: 'msg'
					    ,time: 2 //2秒后自动关闭
                      });
                    return;
                }

                if(time_limit === '' || time_limit === 0) {
                    layer.open({
					    content: '请输入贷款期限'
					    ,skin: 'msg'
					    ,time: 2 //2秒后自动关闭
                      });
                    return;
                }
                if(name === ''){
                    layer.open({
					    content: '请输入姓名'
					    ,skin: 'msg'
					    ,time: 2 //2秒后自动关闭
                      });
                    return;
                }

                if(!isCode(code)){
                    layer.open({
					    content: '请输入正确验证码'
					    ,skin: 'msg'
					    ,time: 2 //2秒后自动关闭
                      });
                    return;
                }
                
                if(purpose === ''){
                    layer.open({
					    content: '请输入贷款目的'
					    ,skin: 'msg'
					    ,time: 2 //2秒后自动关闭
                      });
                    return;
                }

                var loading = layer.open({type: 2});

                $.ajax({
                    type:"POST",
                    url:"/apply/{{ $product->id or 0 }}",
                    data:{ verify_code: code, mobile: mobile, name: name, purpose: purpose,
                         _token: csrf_token, amount: amount, time_limit: time_limit },
                    dataType:'json',
                    success:function(data){
                        layer.close(loading);
                        if (0 == data.code) {
                            layer.open({
                                content: '提交成功，服务人员将在一个工作日内与您联系'
                                ,btn: '我知道了'
                                ,yes: function(index){
                                    window.open('/order','_self');
                                }
                            });
                        } else {
                            layer.open({
                                content: data.message
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                            });
                        }
                    
                    }
                });
//					
			});
			
			
			
			$('#code_btn').click(function(){
				var mobile = $('#mobile').val();
			
				if (!validatePhone(mobile)) {
                    layer.open({
				    content: '请输入正确的手机号码'
				    ,skin: 'msg'
				    ,time: 2 //2秒后自动关闭
                  });
                  return;
                }
                if(flag === false){
                    return;
                }
                layer.open({
                            content: '短信已发送'
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                        });

                $.ajax({
                    type:"get",
                    url: "/sms/send",
                    dataType: "json",
                    data:{
                        sms_code_key : '{{ $sms_code_key }}',
                        mobile : mobile
                    },
                    success:function(data){
                        
                        if(data.code === 0){
                            
                            resetCode();
                            $('footer').addClass("succeed");
                        } else {
                            layer.open({
                            content: data.message
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                            });
                        }
                    },
                    error: function(){
                        layer.open({
                            content: '网络故障'
                            ,skin: 'msg'
                            ,time: 2 //2秒后自动关闭
                            });
                    }
                });	
			});
			
			//倒计时
			var flag=true;
			function resetCode(){
				var second=60;
				var timer=null;
				clearInterval(timer);
				if(flag){
					flag=false;
					timer=setInterval(function(){
						if(second>0){
							second--;
							$('#code_btn').html(second+"秒后重发");
							
						}else{
							second=60;
							flag=true;
							clearInterval(timer);
							$('#code_btn').html("获取验证码");
						}
					},1000);
				}
           }
           // 修改贷款金额&期限
           $("#amount-limit").click(function(){
                layer.open({
                    title: [
                    '修改贷款金额和期限',
                    'background-color: #f2f2f2; color:#9c9c9c;'
                    ]
                    ,content: '这里是任意内容'
                    ,btn: ['确认','关闭']
                    ,yes: function(index){
                        layer.close(index);
                    }
                });
           });
			
    	});
    	
	    	
	    </script>
	    
	</body>
</html>



<?php
/**
 * Created by PhpStorm.
 * User: liwei
 * Date: 2018/1/23
 * Time: 上午10:48
 */
namespace App\Http\Controllers\H5;

use App\Helpers\ErrorCode;
use App\Services\AddressService;
use App\Services\ConfigService;
use App\Services\LoanService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Validator;

class LoanController extends BaseController
{
    // 短信key
    protected $smsCodeKey = 'loan';

    protected $AuthUserAddressKey = 'AuthUserAddress';

    /**
     * 申请列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function Index(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    //'id_no' => 'required',
                    'amount' => 'required',
                    'time_limit' => 'required',
                    'purpose' => 'required',
                    'mobile' => 'required',
                    'verify_code' => 'required',
                ]
            );
            
            if ($validator->fails()) {
                $errors = $validator->errors();
                return output('', ErrorCode::REQUEST_PARAM_ERROR, $errors->first(), $errors->first());
            }

            $saveData = $request->only(['name', 'amount', 'time_limit', 'purpose', 'mobile']);
            if (false === check_mobile($saveData['mobile'])) {
                return output('', ErrorCode::REQUEST_PARAM_ERROR, '请输入正确的手机号码', '请输入正确的手机号码');
            }

            //if (false === check_idcard($saveData['id_no']))
            //    return output('', ErrorCode::REQUEST_PARAM_ERROR, '请输入正确的身份证号码', '请输入正确的身份证号码');

            $verifyCode = $request->input('verify_code');

            $cacheKey = $this->smsCodeKey . $saveData['mobile'];
            if (!Cache::has($cacheKey) || Cache::get($cacheKey) != $verifyCode) {
                return output('', ErrorCode::REQUEST_PARAM_ERROR, '验证码不正确', '验证码不正确');
            }

            $saveData['created_at'] = time();
            try {
                DB::table('loan_apply_item')->insert($saveData);
            } catch (\Exception $e) {
                return output('', ErrorCode::DB_ERROR_INSERT, '提交失败:' . $e->getMessage(), $e->getMessage());
            }

            return output('');
        }

        $this->outData['sms_code_key'] = $this->smsCodeKey;

        return view('H5.loan.form_apply', $this->outData);
    }

    /**
     * 协议
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Pact()
    {
        return view('H5.loan.pact');
    }

    /**
     * 攻略
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Strategy()
    {
        return view('H5.loan.strategy');
    }

    /**
     * 优势
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Advantage()
    {
        return view('H5.loan.advantage');
    }

    /**
     * 授权
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function UserAuthorize(Request $request)
    {
        $AuthUser = $this->outData['user'];

        if ($request->isMethod('post')) {
            $userService = new UserService();
            // 去彩富api拉取用户身份证信息, 存入数据库
            $userService->getCaiFuUser($AuthUser['customer_id']);
            // 同意授权 存入 授权状态，授权时间(可看做注册时间)
            $saveData = [
                'authorize' => 1,
                'source_code' => $AuthUser['source_code'],
                'authorized_at' => time()
            ];

            $result = $userService->updateUserInfo($AuthUser['user_id'], $saveData);
            if (false === $result) {
                return output('', $userService->getErrorCode(), $userService->getErrorMsg(), $userService->getLogMsg());
            }

            return output(['product' => $request->input('product', 0)]);
        }

        return view('H5.loan.authorize_new', ['product' => $request->input('product', 0)]);
    }

    /**
     * 贷款首页
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Home(Request $request)
    {
        $UserInfo = $this->outData['user'];

        //$request->session()->forget($this->AuthUserAddressKey);
        $address = [];
        $AddressService = new AddressService();
        if ($request->input('community_uuid')) {

            $communityUuid = $request->input('community_uuid');
            $communityInfo = $AddressService->getCommunityInfo($communityUuid);
            if ($communityInfo) {
                $address = [
                    'province_code' => $communityInfo['provincecode'],
                    'province_name' => $communityInfo['province'],
                    'city_code' => $communityInfo['citycode'],
                    'city_name' => $communityInfo['city'],
                    'region_code' => $communityInfo['regioncode'],
                    'region_name' => $communityInfo['region'],
                    'community_name' => $communityInfo['name'],
                    'community_uuid' => $communityInfo['uuid'],
                ];
                session([$this->AuthUserAddressKey => $address]);
            }
        }

        if (session()->has($this->AuthUserAddressKey)) {
            $address = session()->get($this->AuthUserAddressKey);
        } else {
            $communityInfo = $AddressService->getCommunityInfo($UserInfo['community_uuid']);
            if ($communityInfo) {
                $address = [
                    'province_code' => $communityInfo['provincecode'],
                    'province_name' => $communityInfo['province'],
                    'city_code' => $communityInfo['citycode'],
                    'city_name' => $communityInfo['city'],
                    'region_code' => $communityInfo['regioncode'],
                    'region_name' => $communityInfo['region'],
                    'community_name' => $communityInfo['name'],
                    'community_uuid' => $communityInfo['uuid'],
                ];

                session([$this->AuthUserAddressKey => $address]);
            }
        }

        $this->outData['address'] = $address;

        $cityCode = isset($address['city_code']) ? $address['city_code'] : '';
        $communityUuid = isset($address['community_uuid']) ? $address['community_uuid'] : '';
        //banner
        $configService = new ConfigService();
        $this->outData['banners'] = $configService->getUserRecourse($UserInfo['customer_id'], $communityUuid);
        $LoanService = new LoanService();
        $product = $LoanService->getUserProduct($UserInfo['user_id'], $cityCode, $UserInfo['customer_id']);

        // 获取用户
        $UserService = new UserService();
        $user = $UserService->getUserInfo($UserInfo['user_id']);

        $this->outData['quota'] = $product['quota'];
        $this->outData['list'] = $product['list'];
        $this->outData['user'] = $user;
        //dd($this->outData);

        return view('H5.loan.home', $this->outData);
    }

    /**
     * 提升额度
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Quota(Request $request)
    {
        $AuthUser = $this->outData['user'];

        $UserService = new UserService();
        $userInfo = $UserService->getUserInfo($AuthUser['user_id']);

        if ($request->isMethod('post')) {
            if ($userInfo['real_name'] == '') {
                $name = $request->input('name');
                $idNo = $request->input('id_no');
                if($idNo && !check_idcard($idNo)){
                    return output('', ErrorCode::REQUEST_PARAM_ERROR, '请输入正确的身份证号码', '请输入正确的身份证号码');
                }
                if ($name && $idNo) {
                    $UserService->updateUserIdCard($AuthUser['user_id'], ['real_name' => $name, 'idcard_number' => $idNo]);
                }
            }

            $saveData = [
                'estate' => $request->input('estate', 0),
                'car' => $request->input('car', 0),
                'marry' => $request->input('marry', 0),
                'urgent_name' => $request->input('urgent_name'),
                'urgent_mobile' => $request->input('urgent_mobile'),
            ];

            $UserService->updateUserInfo($AuthUser['user_id'], $saveData);

            if (session()->has($this->AuthUserAddressKey)) {
                $address = session()->get($this->AuthUserAddressKey);
            } else {
                $AddressService = new AddressService();
                $communityInfo = $AddressService->getCommunityInfo($AuthUser['community_uuid']);
                if ($communityInfo) {
                    $address = [
                        'province_code' => $communityInfo['provincecode'],
                        'province_name' => $communityInfo['province'],
                        'city_code' => $communityInfo['citycode'],
                        'city_name' => $communityInfo['city'],
                        'region_code' => $communityInfo['regioncode'],
                        'region_name' => $communityInfo['region'],
                        'community_name' => $communityInfo['name'],
                        'community_uuid' => $communityInfo['uuid'],
                    ];

                    session([$this->AuthUserAddressKey => $address]);
                }
            }

            $cityCode = isset($address['city_code']) ? $address['city_code'] : '';

            $LoanService = new LoanService();
            $product = $LoanService->getUserProduct($AuthUser['user_id'], $cityCode, $AuthUser['customer_id']);

            return output($product['quota']);

        }

        // $this->outData['real_name'] = $userInfo['real_name'];
        //$this->outData['other'] = $userInfo['other'];
        return view('H5.loan.form_quota', ['userInfo' => $userInfo]);
    }

    /**
     * 用户提交申请
     * @param Request $request
     * @param $productId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function Apply(Request $request, $productId)
    {
        $AuthUser = $this->outData['user'];
        $UserService = new UserService();
        $userInfo = $UserService->getUserInfo($AuthUser['user_id']);
        $LoanService = new LoanService();
        $product = $LoanService->getProduct(['id' => $productId]);
        if ($request->isMethod('post')) {

            $message = [
                'amount.required' => '请填写借款金额',
                'amount.integer' => '借款金额必须是整数',
                'time_limit.max' => '借款期限不能超出127个月',
                'time_limit.min' => '借款期限至少1个月',
                'time_limit.required' => '请填写借款期限',
                'time_limit.integer' => '借款期限必须是整数',
                'verify_code.integer' => '短信验证码必须是数字',
                'purpose.required' => '请填写贷款目的',
            ];

            $validator = Validator::make(
                $request->all(),
                [
                    'amount' => 'required|integer',
                    'time_limit' => 'required|integer|min:1|max:127',
                    'purpose' => 'required',
                    'mobile' => 'required',
                    'verify_code' => 'required|integer',
                ],
                $message
            );

            if ($validator->fails()) {
                $errors = $validator->errors();
                return output('', ErrorCode::REQUEST_PARAM_ERROR, $errors->first(), $errors->first());
            }

            if (empty($userInfo['real_name'])) {
                $name = $request->input('name');
                //$idNo = $request->input('id_no');

                if ($name == '') {
                    return output('', ErrorCode::REQUEST_PARAM_ERROR, '姓名不能为空', '姓名不能为空0');
                }

                // if (!check_idcard($idNo)) {
                //     return output('', ErrorCode::REQUEST_PARAM_ERROR, '请输入正确的身份证号码', '请输入正确的身份证号码0');
                // }

                $UserService->updateUserIdCard($AuthUser['user_id'], ['real_name' => $name]);
            }
            // 判断推荐人手机是否存在
            // $recommandMobile = $request->input('recommand','');
            // if ($recommandMobile) {
            //     if (!check_mobile($recommandMobile)) {
            //         return output('', ErrorCode::REQUEST_PARAM_ERROR, '请输入正确的推荐人手机号', '请输入正确的推荐人手机号');
            //     }

            // }

            // if ($validator->fails()) {
            //     $errors = $validator->errors();
            //     return output('', ErrorCode::REQUEST_PARAM_ERROR, $errors->first(), $errors->first());
            // }

            $saveData = $request->only(['amount', 'time_limit', 'purpose', 'mobile']);
            $saveData['address'] = session($this->AuthUserAddressKey) ?? '';
            $saveData['community_uuid'] = $AuthUser['community_uuid'] ?? '';
            $saveData['source_code'] = $AuthUser['source_code'];
            
            if (false === check_mobile($saveData['mobile'])) {
                return output('', ErrorCode::REQUEST_PARAM_ERROR, '请输入正确的手机号码', '请输入正确的手机号码');
            }
            // 核对发送短信的手机与登录的手机是否一样
            if($request->input('mobile') != $AuthUser['mobile']){
                return output('', ErrorCode::REQUEST_PARAM_ERROR, '请输入本人手机号码', '请输入本人手机号码');
            }

            $verifyCode = $request->input('verify_code');

            // 短信校验
            $cacheKey = $this->smsCodeKey . $saveData['mobile'];
            if (!Cache::has($cacheKey) || Cache::get($cacheKey) != $verifyCode) {
                return output('', ErrorCode::REQUEST_PARAM_ERROR, '验证码不正确', '验证码不正确');
            }
           

            //核对用户贷款不能超过限制
            $ok = $LoanService->checkUserProductQuota($AuthUser['user_id'], session($this->AuthUserAddressKey)['city_code'], $productId, $request->input('amount'));
            if (false === $ok) {
                return output('', $LoanService->getErrorCode(), $LoanService->getErrorMsg(), $LoanService->getLogMsg());
            }

            $result = $LoanService->createOrder($productId, $saveData, $AuthUser);
            if (false === $result) {
                return output('', $LoanService->getErrorCode(), $LoanService->getErrorMsg(), $LoanService->getLogMsg());
            }

            return output('');
        }
        
        $this->outData['real_name'] = $userInfo;
        $this->outData['sms_code_key'] = $this->smsCodeKey;
        $this->outData['product'] = $product;
        // 计算用户当前产品的额度
        $userProductQuota = $LoanService->getUserProductQuota($AuthUser['user_id'], session($this->AuthUserAddressKey)['city_code'], $productId);
        if (false === $userProductQuota) {
            //return output('', $LoanService->getErrorCode(), $LoanService->getErrorMsg(), $LoanService->getLogMsg());
            dd('未获取到您的贷款额度');
        }

        $this->outData['user_product_quota'] = $userProductQuota;
        return view('H5.loan.form_user_apply', $this->outData);
    }

    /**
     * 订单
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Order(Request $request)
    {
        $AuthUser = $this->outData['user'];
        $LoanService = new LoanService();
        $orders = $LoanService->getOrder(['user_id' => $AuthUser['user_id']]);
        $count = $LoanService->countOrder(['user_id' => $AuthUser['user_id']]);
        $products = $LoanService->getProductMap();
        $this->outData['orders'] = $orders;
        $this->outData['count'] = $count;
        $this->outData['products'] = $products;
        
        return view('H5.loan.order', $this->outData);
    }

    /**
     * 附近小区
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function NearbyCommunity(Request $request)
    {
        $AuthUser = $this->outData['user'];

        $lat = $request->input('lat');
        $lng = $request->input('lng');

        $lat = $lat ? $lat : '22.626235485147106';
        $lng = $lng ? $lng : '114.04013247747683';

        $AddressService = new AddressService();
        $list = $AddressService->getNearbyCommunity($lat, $lng, 5);
        if (false === $list) {
            $list = [];
        }

        $this->outData['list'] = $list;

        $address = [];
        if (session()->has($this->AuthUserAddressKey)) {
            $address = session()->get($this->AuthUserAddressKey);
        } else {
            $AddressService = new AddressService();
            $communityInfo = $AddressService->getCommunityInfo($AuthUser['community_uuid']);
            if ($communityInfo) {
                $address = [
                    'province_code' => $communityInfo['provincecode'],
                    'province_name' => $communityInfo['province'],
                    'city_code' => $communityInfo['citycode'],
                    'city_name' => $communityInfo['city'],
                    'region_code' => $communityInfo['regioncode'],
                    'region_name' => $communityInfo['region'],
                    'community_name' => $communityInfo['name'],
                    'community_uuid' => $communityInfo['uuid'],
                ];

                session([$this->AuthUserAddressKey => $address]);
            }
        }

        $this->outData['address'] = $address;

        //dd($this->outData);

        return view('H5.loan.address', $this->outData);
    }

    /**
     * 推荐人奖励规则说明
     * @return view
     */
    public function showRule()
    {
        return view('H5.loan.rule');
    }

}
