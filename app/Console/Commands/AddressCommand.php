<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TestModel\CityModel;
use App\TestModel\DistrictModel;

class AddressCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'address:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '写入地址';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {	
    	// set_time_limit(0);
     //    $arr = $this->getArray();
    	// for ($i = 1; $i < 35; $i++) {
    	// 	if ($arr['province'][$i] == null ) {
    	// 		continue;
    	// 	}
    	// 	$provinceArr = $arr['province'][$i];
    	// 	$provinceName = $provinceArr['@attributes']['name'];
    	// 	$this->info("开始存入省份：".$provinceName);
    	// 	//存省份名字 获得id
    	// 	$proResult = CityModel::create(['name' => $provinceName]);
    	// 	if (isset($provinceArr['city'])) {
    	// 		$cityArr = $provinceArr['city'];
    	// 		//dd($cityArr);
    	// 		foreach ($cityArr as $key => $value) {
    	// 			$cityName = $value['@attributes']['name'];
    	// 			//$cityValue = $value['@attributes']['value'];
    	// 			$this->info("开始存入城市：-- ".$cityName);
    	// 			$cityResult = CityModel::create(['name' => $cityName, 'pid' => $proResult->id]);
    	// 			//dd($value);
    	// 			if(isset($value['county'])) {
    	// 				//遍历城市的区
    	// 				//dd($value['county']);
    	// 				foreach ($value['county'] as $key => $value) {
    	// 					if (isset($value['@attributes'])) {
    	// 						$districtArr = $value['@attributes'];
    	// 						$this->info("开始存入区县：----------- ".$districtArr['name']);
    	// 						DistrictModel::create(['name' => $districtArr['name'],
     // 											'city_id' => $cityResult->id 
    	// 										]);
    	// 					}
    	// 				}
    	// 			}
    	// 		}
    	// 	}    		
    	// }
    	//完成

      set_time_limit(0);
    $citys = CityModel::where('pid', '>', 0)->whereNotNull('address_baixing')->get();
    foreach ($citys as $key => $value) {
      $this->info("开始爬取城市:".$value->name);
      $this->storeDistrict($value);
    }

    $this->info("采集完毕----------------------------------------------------------");

    }

  public function storeDistrict($city)
  {
    $this->info("解析城市区县...");
    $host = "http://127.0.0.1:1234/module/collect/district?city=".$city->address_baixing;
    //http://127.0.0.1:1234/module/collect/district?city=sz
    $json = file_get_contents($host);
    $obj = json_decode($json);
    sleep(10);
    $this->info("暂停10s............");
    if ($obj->code == 0) {
      $arr = $obj->data;
      foreach($arr as $key =>  $value) {
          $valueArr = explode("|", $value);
          $valueName = $valueArr[1];
          $hrefArr = explode("/", $valueArr[0]);
          $valueHref = $hrefArr[2];
          $this->info("正在匹配区名: ".$valueName);
          $dbDistrict = DistrictModel::where('city_id', $city->id)->where('name', 'like', $valueName.'%')->get();
          if ($dbDistrict->isEmpty()) {
            continue;
          }
          $dbDistrict->first()->update(['address_baixing' => $valueHref]);
      }
    } else {
      $this->info("解析json没有获得任何数据。");
    }
  }

	public function getArray()
    {
    	$str =<<<data
<?xml version="1.0" encoding="UTF-8"?> 
 <address>
  <province name="省/直辖市">
 <city name="市">
  <county name="区/县" /> 
  </city>
 
  </province>
 <province name="北京市">
 <city name="北京市">
  <county name="东城区" /> 
  <county name="西城区" /> 
  <county name="崇文区" /> 
  <county name="宣武区" /> 
  <county name="朝阳区" /> 
  <county name="丰台区" /> 
  <county name="石景山区" /> 
  <county name="海淀区" /> 
  <county name="门头沟区" /> 
  <county name="房山区" /> 
  <county name="通州区" /> 
  <county name="顺义区" /> 
  <county name="昌平区" /> 
  <county name="大兴区" /> 
  <county name="怀柔区" /> 
  <county name="平谷区" /> 
  </city>
 <city name="县">
  <county name="密云县" /> 
  <county name="延庆县" /> 
  </city>
  </province>
 <province name="天津市">
 <city name="天津市">
  <county name="和平区" /> 
  <county name="河东区" /> 
  <county name="河西区" /> 
  <county name="南开区" /> 
  <county name="河北区" /> 
  <county name="红桥区" /> 
  <county name="塘沽区" /> 
  <county name="汉沽区" /> 
  <county name="大港区" /> 
  <county name="东丽区" /> 
  <county name="西青区" /> 
  <county name="津南区" /> 
  <county name="北辰区" /> 
  <county name="武清区" /> 
  <county name="宝坻区" /> 
  </city>
 <city name="县">
  <county name="宁河县" /> 
  <county name="静海县" /> 
  <county name="蓟县" /> 
  </city>
  </province>
 <province name="河北省">
 <city name="石家庄市">
  <county name="市辖区" /> 
  <county name="长安区" /> 
  <county name="桥东区" /> 
  <county name="桥西区" /> 
  <county name="新华区" /> 
  <county name="井陉矿区" /> 
  <county name="裕华区" /> 
  <county name="井陉县" /> 
  <county name="正定县" /> 
  <county name="栾城县" /> 
  <county name="行唐县" /> 
  <county name="灵寿县" /> 
  <county name="高邑县" /> 
  <county name="深泽县" /> 
  <county name="赞皇县" /> 
  <county name="无极县" /> 
  <county name="平山县" /> 
  <county name="元氏县" /> 
  <county name="赵县" /> 
  <county name="辛集市" /> 
  <county name="藁城市" /> 
  <county name="晋州市" /> 
  <county name="新乐市" /> 
  <county name="鹿泉市" /> 
  </city>
 <city name="唐山市">
  <county name="市辖区" /> 
  <county name="路南区" /> 
  <county name="路北区" /> 
  <county name="古冶区" /> 
  <county name="开平区" /> 
  <county name="丰南区" /> 
  <county name="丰润区" /> 
  <county name="滦县" /> 
  <county name="滦南县" /> 
  <county name="乐亭县" /> 
  <county name="迁西县" /> 
  <county name="玉田县" /> 
  <county name="唐海县" /> 
  <county name="遵化市" /> 
  <county name="迁安市" /> 
  </city>
 <city name="秦皇岛市">
  <county name="市辖区" /> 
  <county name="海港区" /> 
  <county name="山海关区" /> 
  <county name="北戴河区" /> 
  <county name="青龙满族自治县" /> 
  <county name="昌黎县" /> 
  <county name="抚宁县" /> 
  <county name="卢龙县" /> 
  </city>
 <city name="邯郸市">
  <county name="市辖区" /> 
  <county name="邯山区" /> 
  <county name="丛台区" /> 
  <county name="复兴区" /> 
  <county name="峰峰矿区" /> 
  <county name="邯郸县" /> 
  <county name="临漳县" /> 
  <county name="成安县" /> 
  <county name="大名县" /> 
  <county name="涉县" /> 
  <county name="磁县" /> 
  <county name="肥乡县" /> 
  <county name="永年县" /> 
  <county name="邱县" /> 
  <county name="鸡泽县" /> 
  <county name="广平县" /> 
  <county name="馆陶县" /> 
  <county name="魏县" /> 
  <county name="曲周县" /> 
  <county name="武安市" /> 
  </city>
 <city name="邢台市">
  <county name="市辖区" /> 
  <county name="桥东区" /> 
  <county name="桥西区" /> 
  <county name="邢台县" /> 
  <county name="临城县" /> 
  <county name="内丘县" /> 
  <county name="柏乡县" /> 
  <county name="隆尧县" /> 
  <county name="任县" /> 
  <county name="南和县" /> 
  <county name="宁晋县" /> 
  <county name="巨鹿县" /> 
  <county name="新河县" /> 
  <county name="广宗县" /> 
  <county name="平乡县" /> 
  <county name="威县" /> 
  <county name="清河县" /> 
  <county name="临西县" /> 
  <county name="南宫市" /> 
  <county name="沙河市" /> 
  </city>
 <city name="保定市">
  <county name="市辖区" /> 
  <county name="新市区" /> 
  <county name="北市区" /> 
  <county name="南市区" /> 
  <county name="满城县" /> 
  <county name="清苑县" /> 
  <county name="涞水县" /> 
  <county name="阜平县" /> 
  <county name="徐水县" /> 
  <county name="定兴县" /> 
  <county name="唐县" /> 
  <county name="高阳县" /> 
  <county name="容城县" /> 
  <county name="涞源县" /> 
  <county name="望都县" /> 
  <county name="安新县" /> 
  <county name="易县" /> 
  <county name="曲阳县" /> 
  <county name="蠡县" /> 
  <county name="顺平县" /> 
  <county name="博野县" /> 
  <county name="雄县" /> 
  <county name="涿州市" /> 
  <county name="定州市" /> 
  <county name="安国市" /> 
  <county name="高碑店市" /> 
  </city>
 <city name="张家口市">
  <county name="市辖区" /> 
  <county name="桥东区" /> 
  <county name="桥西区" /> 
  <county name="宣化区" /> 
  <county name="下花园区" /> 
  <county name="宣化县" /> 
  <county name="张北县" /> 
  <county name="康保县" /> 
  <county name="沽源县" /> 
  <county name="尚义县" /> 
  <county name="蔚县" /> 
  <county name="阳原县" /> 
  <county name="怀安县" /> 
  <county name="万全县" /> 
  <county name="怀来县" /> 
  <county name="涿鹿县" /> 
  <county name="赤城县" /> 
  <county name="崇礼县" /> 
  </city>
 <city name="承德市">
  <county name="市辖区" /> 
  <county name="双桥区" /> 
  <county name="双滦区" /> 
  <county name="鹰手营子矿区" /> 
  <county name="承德县" /> 
  <county name="兴隆县" /> 
  <county name="平泉县" /> 
  <county name="滦平县" /> 
  <county name="隆化县" /> 
  <county name="丰宁满族自治县" /> 
  <county name="宽城满族自治县" /> 
  <county name="围场满族蒙古族自治县" /> 
  </city>
 <city name="沧州市">
  <county name="市辖区" /> 
  <county name="新华区" /> 
  <county name="运河区" /> 
  <county name="沧县" /> 
  <county name="青县" /> 
  <county name="东光县" /> 
  <county name="海兴县" /> 
  <county name="盐山县" /> 
  <county name="肃宁县" /> 
  <county name="南皮县" /> 
  <county name="吴桥县" /> 
  <county name="献县" /> 
  <county name="孟村回族自治县" /> 
  <county name="泊头市" /> 
  <county name="任丘市" /> 
  <county name="黄骅市" /> 
  <county name="河间市" /> 
  </city>
 <city name="廊坊市">
  <county name="市辖区" /> 
  <county name="安次区" /> 
  <county name="广阳区" /> 
  <county name="固安县" /> 
  <county name="永清县" /> 
  <county name="香河县" /> 
  <county name="大城县" /> 
  <county name="文安县" /> 
  <county name="大厂回族自治县" /> 
  <county name="霸州市" /> 
  <county name="三河市" /> 
  </city>
 <city name="衡水市">
  <county name="市辖区" /> 
  <county name="桃城区" /> 
  <county name="枣强县" /> 
  <county name="武邑县" /> 
  <county name="武强县" /> 
  <county name="饶阳县" /> 
  <county name="安平县" /> 
  <county name="故城县" /> 
  <county name="景县" /> 
  <county name="阜城县" /> 
  <county name="冀州市" /> 
  <county name="深州市" /> 
  </city>
  </province>
 <province name="山西省">
 <city name="太原市">
  <county name="市辖区" /> 
  <county name="小店区" /> 
  <county name="迎泽区" /> 
  <county name="杏花岭区" /> 
  <county name="尖草坪区" /> 
  <county name="万柏林区" /> 
  <county name="晋源区" /> 
  <county name="清徐县" /> 
  <county name="阳曲县" /> 
  <county name="娄烦县" /> 
  <county name="古交市" /> 
  </city>
 <city name="大同市">
  <county name="市辖区" /> 
  <county name="城区" /> 
  <county name="矿区" /> 
  <county name="南郊区" /> 
  <county name="新荣区" /> 
  <county name="阳高县" /> 
  <county name="天镇县" /> 
  <county name="广灵县" /> 
  <county name="灵丘县" /> 
  <county name="浑源县" /> 
  <county name="左云县" /> 
  <county name="大同县" /> 
  </city>
 <city name="阳泉市">
  <county name="市辖区" /> 
  <county name="城区" /> 
  <county name="矿区" /> 
  <county name="郊区" /> 
  <county name="平定县" /> 
  <county name="盂县" /> 
  </city>
 <city name="长治市">
  <county name="市辖区" /> 
  <county name="城区" /> 
  <county name="郊区" /> 
  <county name="长治县" /> 
  <county name="襄垣县" /> 
  <county name="屯留县" /> 
  <county name="平顺县" /> 
  <county name="黎城县" /> 
  <county name="壶关县" /> 
  <county name="长子县" /> 
  <county name="武乡县" /> 
  <county name="沁县" /> 
  <county name="沁源县" /> 
  <county name="潞城市" /> 
  </city>
 <city name="晋城市">
  <county name="市辖区" /> 
  <county name="城区" /> 
  <county name="沁水县" /> 
  <county name="阳城县" /> 
  <county name="陵川县" /> 
  <county name="泽州县" /> 
  <county name="高平市" /> 
  </city>
 <city name="朔州市">
  <county name="市辖区" /> 
  <county name="朔城区" /> 
  <county name="平鲁区" /> 
  <county name="山阴县" /> 
  <county name="应县" /> 
  <county name="右玉县" /> 
  <county name="怀仁县" /> 
  </city>
 <city name="晋中市">
  <county name="市辖区" /> 
  <county name="榆次区" /> 
  <county name="榆社县" /> 
  <county name="左权县" /> 
  <county name="和顺县" /> 
  <county name="昔阳县" /> 
  <county name="寿阳县" /> 
  <county name="太谷县" /> 
  <county name="祁县" /> 
  <county name="平遥县" /> 
  <county name="灵石县" /> 
  <county name="介休市" /> 
  </city>
 <city name="运城市">
  <county name="市辖区" /> 
  <county name="盐湖区" /> 
  <county name="临猗县" /> 
  <county name="万荣县" /> 
  <county name="闻喜县" /> 
  <county name="稷山县" /> 
  <county name="新绛县" /> 
  <county name="绛县" /> 
  <county name="垣曲县" /> 
  <county name="夏县" /> 
  <county name="平陆县" /> 
  <county name="芮城县" /> 
  <county name="永济市" /> 
  <county name="河津市" /> 
  </city>
 <city name="忻州市">
  <county name="市辖区" /> 
  <county name="忻府区" /> 
  <county name="定襄县" /> 
  <county name="五台县" /> 
  <county name="代县" /> 
  <county name="繁峙县" /> 
  <county name="宁武县" /> 
  <county name="静乐县" /> 
  <county name="神池县" /> 
  <county name="五寨县" /> 
  <county name="岢岚县" /> 
  <county name="河曲县" /> 
  <county name="保德县" /> 
  <county name="偏关县" /> 
  <county name="原平市" /> 
  </city>
 <city name="临汾市">
  <county name="市辖区" /> 
  <county name="尧都区" /> 
  <county name="曲沃县" /> 
  <county name="翼城县" /> 
  <county name="襄汾县" /> 
  <county name="洪洞县" /> 
  <county name="古县" /> 
  <county name="安泽县" /> 
  <county name="浮山县" /> 
  <county name="吉县" /> 
  <county name="乡宁县" /> 
  <county name="大宁县" /> 
  <county name="隰县" /> 
  <county name="永和县" /> 
  <county name="蒲县" /> 
  <county name="汾西县" /> 
  <county name="侯马市" /> 
  <county name="霍州市" /> 
  </city>
 <city name="吕梁市">
  <county name="市辖区" /> 
  <county name="离石区" /> 
  <county name="文水县" /> 
  <county name="交城县" /> 
  <county name="兴县" /> 
  <county name="临县" /> 
  <county name="柳林县" /> 
  <county name="石楼县" /> 
  <county name="岚县" /> 
  <county name="方山县" /> 
  <county name="中阳县" /> 
  <county name="交口县" /> 
  <county name="孝义市" /> 
  <county name="汾阳市" /> 
  </city>
  </province>
 <province name="内蒙古区">
 <city name="呼和浩特市">
  <county name="市辖区" /> 
  <county name="新城区" /> 
  <county name="回民区" /> 
  <county name="玉泉区" /> 
  <county name="赛罕区" /> 
  <county name="土默特左旗" /> 
  <county name="托克托县" /> 
  <county name="和林格尔县" /> 
  <county name="清水河县" /> 
  <county name="武川县" /> 
  </city>
 <city name="包头市">
  <county name="市辖区" /> 
  <county name="东河区" /> 
  <county name="昆都仑区" /> 
  <county name="青山区" /> 
  <county name="石拐区" /> 
  <county name="白云矿区" /> 
  <county name="九原区" /> 
  <county name="土默特右旗" /> 
  <county name="固阳县" /> 
  <county name="达尔罕茂明安联合旗" /> 
  </city>
 <city name="乌海市">
  <county name="市辖区" /> 
  <county name="海勃湾区" /> 
  <county name="海南区" /> 
  <county name="乌达区" /> 
  </city>
 <city name="赤峰市">
  <county name="市辖区" /> 
  <county name="红山区" /> 
  <county name="元宝山区" /> 
  <county name="松山区" /> 
  <county name="阿鲁科尔沁旗" /> 
  <county name="巴林左旗" /> 
  <county name="巴林右旗" /> 
  <county name="林西县" /> 
  <county name="克什克腾旗" /> 
  <county name="翁牛特旗" /> 
  <county name="喀喇沁旗" /> 
  <county name="宁城县" /> 
  <county name="敖汉旗" /> 
  </city>
 <city name="通辽市">
  <county name="市辖区" /> 
  <county name="科尔沁区" /> 
  <county name="科尔沁左翼中旗" /> 
  <county name="科尔沁左翼后旗" /> 
  <county name="开鲁县" /> 
  <county name="库伦旗" /> 
  <county name="奈曼旗" /> 
  <county name="扎鲁特旗" /> 
  <county name="霍林郭勒市" /> 
  </city>
 <city name="鄂尔多斯市">
  <county name="东胜区" /> 
  <county name="达拉特旗" /> 
  <county name="准格尔旗" /> 
  <county name="鄂托克前旗" /> 
  <county name="鄂托克旗" /> 
  <county name="杭锦旗" /> 
  <county name="乌审旗" /> 
  <county name="伊金霍洛旗" /> 
  </city>
 <city name="呼伦贝尔市">
  <county name="市辖区" /> 
  <county name="海拉尔区" /> 
  <county name="阿荣旗" /> 
  <county name="莫力达瓦达斡尔族自治旗" /> 
  <county name="鄂伦春自治旗" /> 
  <county name="鄂温克族自治旗" /> 
  <county name="陈巴尔虎旗" /> 
  <county name="新巴尔虎左旗" /> 
  <county name="新巴尔虎右旗" /> 
  <county name="满洲里市" /> 
  <county name="牙克石市" /> 
  <county name="扎兰屯市" /> 
  <county name="额尔古纳市" /> 
  <county name="根河市" /> 
  </city>
 <city name="巴彦淖尔市">
  <county name="市辖区" /> 
  <county name="临河区" /> 
  <county name="五原县" /> 
  <county name="磴口县" /> 
  <county name="乌拉特前旗" /> 
  <county name="乌拉特中旗" /> 
  <county name="乌拉特后旗" /> 
  <county name="杭锦后旗" /> 
  </city>
 <city name="乌兰察布市">
  <county name="市辖区" /> 
  <county name="集宁区" /> 
  <county name="卓资县" /> 
  <county name="化德县" /> 
  <county name="商都县" /> 
  <county name="兴和县" /> 
  <county name="凉城县" /> 
  <county name="察哈尔右翼前旗" /> 
  <county name="察哈尔右翼中旗" /> 
  <county name="察哈尔右翼后旗" /> 
  <county name="四子王旗" /> 
  <county name="丰镇市" /> 
  </city>
 <city name="兴安盟">
  <county name="乌兰浩特市" /> 
  <county name="阿尔山市" /> 
  <county name="科尔沁右翼前旗" /> 
  <county name="科尔沁右翼中旗" /> 
  <county name="扎赉特旗" /> 
  <county name="突泉县" /> 
  </city>
 <city name="锡林郭勒盟">
  <county name="二连浩特市" /> 
  <county name="锡林浩特市" /> 
  <county name="阿巴嘎旗" /> 
  <county name="苏尼特左旗" /> 
  <county name="苏尼特右旗" /> 
  <county name="东乌珠穆沁旗" /> 
  <county name="西乌珠穆沁旗" /> 
  <county name="太仆寺旗" /> 
  <county name="镶黄旗" /> 
  <county name="正镶白旗" /> 
  <county name="正蓝旗" /> 
  <county name="多伦县" /> 
  </city>
 <city name="阿拉善盟">
  <county name="阿拉善左旗" /> 
  <county name="阿拉善右旗" /> 
  <county name="额济纳旗" /> 
  </city>
  </province>
 <province name="辽宁省">
 <city name="沈阳市">
  <county name="市辖区" /> 
  <county name="和平区" /> 
  <county name="沈河区" /> 
  <county name="大东区" /> 
  <county name="皇姑区" /> 
  <county name="铁西区" /> 
  <county name="苏家屯区" /> 
  <county name="东陵区" /> 
  <county name="新城子区" /> 
  <county name="于洪区" /> 
  <county name="辽中县" /> 
  <county name="康平县" /> 
  <county name="法库县" /> 
  <county name="新民市" /> 
  </city>
 <city name="大连市">
  <county name="市辖区" /> 
  <county name="中山区" /> 
  <county name="西岗区" /> 
  <county name="沙河口区" /> 
  <county name="甘井子区" /> 
  <county name="旅顺口区" /> 
  <county name="金州区" /> 
  <county name="长海县" /> 
  <county name="瓦房店市" /> 
  <county name="普兰店市" /> 
  <county name="庄河市" /> 
  </city>
 <city name="鞍山市">
  <county name="市辖区" /> 
  <county name="铁东区" /> 
  <county name="铁西区" /> 
  <county name="立山区" /> 
  <county name="千山区" /> 
  <county name="台安县" /> 
  <county name="岫岩满族自治县" /> 
  <county name="海城市" /> 
  </city>
 <city name="抚顺市">
  <county name="市辖区" /> 
  <county name="新抚区" /> 
  <county name="东洲区" /> 
  <county name="望花区" /> 
  <county name="顺城区" /> 
  <county name="抚顺县" /> 
  <county name="新宾满族自治县" /> 
  <county name="清原满族自治县" /> 
  </city>
 <city name="本溪市">
  <county name="市辖区" /> 
  <county name="平山区" /> 
  <county name="溪湖区" /> 
  <county name="明山区" /> 
  <county name="南芬区" /> 
  <county name="本溪满族自治县" /> 
  <county name="桓仁满族自治县" /> 
  </city>
 <city name="丹东市">
  <county name="市辖区" /> 
  <county name="元宝区" /> 
  <county name="振兴区" /> 
  <county name="振安区" /> 
  <county name="宽甸满族自治县" /> 
  <county name="东港市" /> 
  <county name="凤城市" /> 
  </city>
 <city name="锦州市">
  <county name="市辖区" /> 
  <county name="古塔区" /> 
  <county name="凌河区" /> 
  <county name="太和区" /> 
  <county name="黑山县" /> 
  <county name="义县" /> 
  <county name="凌海市" /> 
  <county name="北宁市" /> 
  </city>
 <city name="营口市">
  <county name="市辖区" /> 
  <county name="站前区" /> 
  <county name="西市区" /> 
  <county name="鲅鱼圈区" /> 
  <county name="老边区" /> 
  <county name="盖州市" /> 
  <county name="大石桥市" /> 
  </city>
 <city name="阜新市">
  <county name="市辖区" /> 
  <county name="海州区" /> 
  <county name="新邱区" /> 
  <county name="太平区" /> 
  <county name="清河门区" /> 
  <county name="细河区" /> 
  <county name="阜新蒙古族自治县" /> 
  <county name="彰武县" /> 
  </city>
 <city name="辽阳市">
  <county name="市辖区" /> 
  <county name="白塔区" /> 
  <county name="文圣区" /> 
  <county name="宏伟区" /> 
  <county name="弓长岭区" /> 
  <county name="太子河区" /> 
  <county name="辽阳县" /> 
  <county name="灯塔市" /> 
  </city>
 <city name="盘锦市">
  <county name="市辖区" /> 
  <county name="双台子区" /> 
  <county name="兴隆台区" /> 
  <county name="大洼县" /> 
  <county name="盘山县" /> 
  </city>
 <city name="铁岭市">
  <county name="市辖区" /> 
  <county name="银州区" /> 
  <county name="清河区" /> 
  <county name="铁岭县" /> 
  <county name="西丰县" /> 
  <county name="昌图县" /> 
  <county name="调兵山市" /> 
  <county name="开原市" /> 
  </city>
 <city name="朝阳市">
  <county name="市辖区" /> 
  <county name="双塔区" /> 
  <county name="龙城区" /> 
  <county name="朝阳县" /> 
  <county name="建平县" /> 
  <county name="喀喇沁左翼蒙古族自治县" /> 
  <county name="北票市" /> 
  <county name="凌源市" /> 
  </city>
 <city name="葫芦岛市">
  <county name="市辖区" /> 
  <county name="连山区" /> 
  <county name="龙港区" /> 
  <county name="南票区" /> 
  <county name="绥中县" /> 
  <county name="建昌县" /> 
  <county name="兴城市" /> 
  </city>
  </province>
 <province name="吉林省">
 <city name="长春市">
  <county name="市辖区" /> 
  <county name="南关区" /> 
  <county name="宽城区" /> 
  <county name="朝阳区" /> 
  <county name="二道区" /> 
  <county name="绿园区" /> 
  <county name="双阳区" /> 
  <county name="农安县" /> 
  <county name="九台市" /> 
  <county name="榆树市" /> 
  <county name="德惠市" /> 
  </city>
 <city name="吉林市">
  <county name="市辖区" /> 
  <county name="昌邑区" /> 
  <county name="龙潭区" /> 
  <county name="船营区" /> 
  <county name="丰满区" /> 
  <county name="永吉县" /> 
  <county name="蛟河市" /> 
  <county name="桦甸市" /> 
  <county name="舒兰市" /> 
  <county name="磐石市" /> 
  </city>
 <city name="四平市">
  <county name="市辖区" /> 
  <county name="铁西区" /> 
  <county name="铁东区" /> 
  <county name="梨树县" /> 
  <county name="伊通满族自治县" /> 
  <county name="公主岭市" /> 
  <county name="双辽市" /> 
  </city>
 <city name="辽源市">
  <county name="市辖区" /> 
  <county name="龙山区" /> 
  <county name="西安区" /> 
  <county name="东丰县" /> 
  <county name="东辽县" /> 
  </city>
 <city name="通化市">
  <county name="市辖区" /> 
  <county name="东昌区" /> 
  <county name="二道江区" /> 
  <county name="通化县" /> 
  <county name="辉南县" /> 
  <county name="柳河县" /> 
  <county name="梅河口市" /> 
  <county name="集安市" /> 
  </city>
 <city name="白山市">
  <county name="市辖区" /> 
  <county name="八道江区" /> 
  <county name="抚松县" /> 
  <county name="靖宇县" /> 
  <county name="长白朝鲜族自治县" /> 
  <county name="江源县" /> 
  <county name="临江市" /> 
  </city>
 <city name="松原市">
  <county name="市辖区" /> 
  <county name="宁江区" /> 
  <county name="前郭尔罗斯蒙古族自治县" /> 
  <county name="长岭县" /> 
  <county name="乾安县" /> 
  <county name="扶余县" /> 
  </city>
 <city name="白城市">
  <county name="市辖区" /> 
  <county name="洮北区" /> 
  <county name="镇赉县" /> 
  <county name="通榆县" /> 
  <county name="洮南市" /> 
  <county name="大安市" /> 
  </city>
 <city name="延边自治州">
  <county name="延吉市" /> 
  <county name="图们市" /> 
  <county name="敦化市" /> 
  <county name="珲春市" /> 
  <county name="龙井市" /> 
  <county name="和龙市" /> 
  <county name="汪清县" /> 
  <county name="安图县" /> 
  </city>
  </province>
 <province name="黑龙江省">
 <city name="哈尔滨市">
  <county name="市辖区" /> 
  <county name="道里区" /> 
  <county name="南岗区" /> 
  <county name="道外区" /> 
  <county name="香坊区" /> 
  <county name="动力区" /> 
  <county name="平房区" /> 
  <county name="松北区" /> 
  <county name="呼兰区" /> 
  <county name="依兰县" /> 
  <county name="方正县" /> 
  <county name="宾县" /> 
  <county name="巴彦县" /> 
  <county name="木兰县" /> 
  <county name="通河县" /> 
  <county name="延寿县" /> 
  <county name="阿城市" /> 
  <county name="双城市" /> 
  <county name="尚志市" /> 
  <county name="五常市" /> 
  </city>
 <city name="齐齐哈尔市">
  <county name="市辖区" /> 
  <county name="龙沙区" /> 
  <county name="建华区" /> 
  <county name="铁锋区" /> 
  <county name="昂昂溪区" /> 
  <county name="富拉尔基区" /> 
  <county name="碾子山区" /> 
  <county name="梅里斯达斡尔族区" /> 
  <county name="龙江县" /> 
  <county name="依安县" /> 
  <county name="泰来县" /> 
  <county name="甘南县" /> 
  <county name="富裕县" /> 
  <county name="克山县" /> 
  <county name="克东县" /> 
  <county name="拜泉县" /> 
  <county name="讷河市" /> 
  </city>
 <city name="鸡西市">
  <county name="市辖区" /> 
  <county name="鸡冠区" /> 
  <county name="恒山区" /> 
  <county name="滴道区" /> 
  <county name="梨树区" /> 
  <county name="城子河区" /> 
  <county name="麻山区" /> 
  <county name="鸡东县" /> 
  <county name="虎林市" /> 
  <county name="密山市" /> 
  </city>
 <city name="鹤岗市">
  <county name="市辖区" /> 
  <county name="向阳区" /> 
  <county name="工农区" /> 
  <county name="南山区" /> 
  <county name="兴安区" /> 
  <county name="东山区" /> 
  <county name="兴山区" /> 
  <county name="萝北县" /> 
  <county name="绥滨县" /> 
  </city>
 <city name="双鸭山市">
  <county name="市辖区" /> 
  <county name="尖山区" /> 
  <county name="岭东区" /> 
  <county name="四方台区" /> 
  <county name="宝山区" /> 
  <county name="集贤县" /> 
  <county name="友谊县" /> 
  <county name="宝清县" /> 
  <county name="饶河县" /> 
  </city>
 <city name="大庆市">
  <county name="市辖区" /> 
  <county name="萨尔图区" /> 
  <county name="龙凤区" /> 
  <county name="让胡路区" /> 
  <county name="红岗区" /> 
  <county name="大同区" /> 
  <county name="肇州县" /> 
  <county name="肇源县" /> 
  <county name="林甸县" /> 
  <county name="杜尔伯特蒙古族自治县" /> 
  </city>
 <city name="伊春市">
  <county name="市辖区" /> 
  <county name="伊春区" /> 
  <county name="南岔区" /> 
  <county name="友好区" /> 
  <county name="西林区" /> 
  <county name="翠峦区" /> 
  <county name="新青区" /> 
  <county name="美溪区" /> 
  <county name="金山屯区" /> 
  <county name="五营区" /> 
  <county name="乌马河区" /> 
  <county name="汤旺河区" /> 
  <county name="带岭区" /> 
  <county name="乌伊岭区" /> 
  <county name="红星区" /> 
  <county name="上甘岭区" /> 
  <county name="嘉荫县" /> 
  <county name="铁力市" /> 
  </city>
 <city name="佳木斯市">
  <county name="市辖区" /> 
  <county name="永红区" /> 
  <county name="向阳区" /> 
  <county name="前进区" /> 
  <county name="东风区" /> 
  <county name="郊区" /> 
  <county name="桦南县" /> 
  <county name="桦川县" /> 
  <county name="汤原县" /> 
  <county name="抚远县" /> 
  <county name="同江市" /> 
  <county name="富锦市" /> 
  </city>
 <city name="七台河市">
  <county name="市辖区" /> 
  <county name="新兴区" /> 
  <county name="桃山区" /> 
  <county name="茄子河区" /> 
  <county name="勃利县" /> 
  </city>
 <city name="牡丹江市">
  <county name="市辖区" /> 
  <county name="东安区" /> 
  <county name="阳明区" /> 
  <county name="爱民区" /> 
  <county name="西安区" /> 
  <county name="东宁县" /> 
  <county name="林口县" /> 
  <county name="绥芬河市" /> 
  <county name="海林市" /> 
  <county name="宁安市" /> 
  <county name="穆棱市" /> 
  </city>
 <city name="黑河市">
  <county name="市辖区" /> 
  <county name="爱辉区" /> 
  <county name="嫩江县" /> 
  <county name="逊克县" /> 
  <county name="孙吴县" /> 
  <county name="北安市" /> 
  <county name="五大连池市" /> 
  </city>
 <city name="绥化市">
  <county name="市辖区" /> 
  <county name="北林区" /> 
  <county name="望奎县" /> 
  <county name="兰西县" /> 
  <county name="青冈县" /> 
  <county name="庆安县" /> 
  <county name="明水县" /> 
  <county name="绥棱县" /> 
  <county name="安达市" /> 
  <county name="肇东市" /> 
  <county name="海伦市" /> 
  </city>
 <city name="大兴安岭地区">
  <county name="呼玛县" /> 
  <county name="塔河县" /> 
  <county name="漠河县" /> 
  </city>
  </province>
 <province name="上海市">
 <city name="上海市">
  <county name="黄浦区" /> 
  <county name="卢湾区" /> 
  <county name="徐汇区" /> 
  <county name="长宁区" /> 
  <county name="静安区" /> 
  <county name="普陀区" /> 
  <county name="闸北区" /> 
  <county name="虹口区" /> 
  <county name="杨浦区" /> 
  <county name="闵行区" /> 
  <county name="宝山区" /> 
  <county name="嘉定区" /> 
  <county name="浦东新区" /> 
  <county name="金山区" /> 
  <county name="松江区" /> 
  <county name="青浦区" /> 
  <county name="南汇区" /> 
  <county name="奉贤区" /> 
  </city>
 <city name="县">
  <county name="崇明县" /> 
  </city>
  </province>
 <province name="江苏省">
 <city name="南京市">
  <county name="市辖区" /> 
  <county name="玄武区" /> 
  <county name="秦淮区" /> 
  <county name="建邺区" /> 
  <county name="鼓楼区" /> 
  <county name="浦口区" /> 
  <county name="六合区" /> 
  <county name="栖霞区" /> 
  <county name="雨花台区" /> 
  <county name="江宁区" /> 
  <county name="溧水县" /> 
  <county name="高淳县" /> 
  </city>
 <city name="无锡市">
  <county name="市辖区" /> 
  <county name="崇安区" /> 
  <county name="南长区" /> 
  <county name="北塘区" /> 
  <county name="锡山区" /> 
  <county name="惠山区" /> 
  <county name="滨湖区" /> 
  <county name="江阴市" /> 
  <county name="宜兴市" /> 
  </city>
 <city name="徐州市">
  <county name="市辖区" /> 
  <county name="鼓楼区" /> 
  <county name="云龙区" /> 
  <county name="九里区" /> 
  <county name="贾汪区" /> 
  <county name="泉山区" /> 
  <county name="丰县" /> 
  <county name="沛县" /> 
  <county name="铜山县" /> 
  <county name="睢宁县" /> 
  <county name="新沂市" /> 
  <county name="邳州市" /> 
  </city>
 <city name="常州市">
  <county name="市辖区" /> 
  <county name="天宁区" /> 
  <county name="钟楼区" /> 
  <county name="戚墅堰区" /> 
  <county name="新北区" /> 
  <county name="武进区" /> 
  <county name="溧阳市" /> 
  <county name="金坛市" /> 
  </city>
 <city name="苏州市">
  <county name="市辖区" /> 
  <county name="姑苏区" /> 
  <county name="虎丘区" /> 
  <county name="吴中区" /> 
  <county name="相城区" /> 
  <county name="吴江区" /> 
  <county name="常熟市" /> 
  <county name="昆山市" /> 
  <county name="太仓市" /> 
  <county name="张家港市" />
  </city>
 <city name="南通市">
  <county name="市辖区" /> 
  <county name="崇川区" /> 
  <county name="港闸区" /> 
  <county name="海安县" /> 
  <county name="如东县" /> 
  <county name="启东市" /> 
  <county name="如皋市" /> 
  <county name="通州市" /> 
  <county name="海门市" /> 
  </city>
 <city name="连云港市">
  <county name="市辖区" /> 
  <county name="连云区" /> 
  <county name="新浦区" /> 
  <county name="海州区" /> 
  <county name="赣榆县" /> 
  <county name="东海县" /> 
  <county name="灌云县" /> 
  <county name="灌南县" /> 
  </city>
 <city name="淮安市">
  <county name="市辖区" /> 
  <county name="清河区" /> 
  <county name="楚州区" /> 
  <county name="淮阴区" /> 
  <county name="清浦区" /> 
  <county name="涟水县" /> 
  <county name="洪泽县" /> 
  <county name="盱眙县" /> 
  <county name="金湖县" /> 
  </city>
 <city name="盐城市">
  <county name="市辖区" /> 
  <county name="亭湖区" /> 
  <county name="盐都区" /> 
  <county name="响水县" /> 
  <county name="滨海县" /> 
  <county name="阜宁县" /> 
  <county name="射阳县" /> 
  <county name="建湖县" /> 
  <county name="东台市" /> 
  <county name="大丰市" /> 
  </city>
 <city name="扬州市">
  <county name="市辖区" /> 
  <county name="广陵区" /> 
  <county name="邗江区" /> 
  <county name="郊区" /> 
  <county name="宝应县" /> 
  <county name="仪征市" /> 
  <county name="高邮市" /> 
  <county name="江都市" /> 
  </city>
 <city name="镇江市">
  <county name="市辖区" /> 
  <county name="京口区" /> 
  <county name="润州区" /> 
  <county name="丹徒区" /> 
  <county name="丹阳市" /> 
  <county name="扬中市" /> 
  <county name="句容市" /> 
  </city>
 <city name="泰州市">
  <county name="市辖区" /> 
  <county name="海陵区" /> 
  <county name="高港区" /> 
  <county name="兴化市" /> 
  <county name="靖江市" /> 
  <county name="泰兴市" /> 
  <county name="姜堰市" /> 
  </city>
 <city name="宿迁市">
  <county name="市辖区" /> 
  <county name="宿城区" /> 
  <county name="宿豫区" /> 
  <county name="沭阳县" /> 
  <county name="泗阳县" /> 
  <county name="泗洪县" /> 
  </city>
  </province>
 <province name="浙江省">
 <city name="杭州市">
  <county name="市辖区" /> 
  <county name="上城区" /> 
  <county name="下城区" /> 
  <county name="江干区" /> 
  <county name="拱墅区" /> 
  <county name="西湖区" /> 
  <county name="滨江区" /> 
  <county name="萧山区" /> 
  <county name="余杭区" /> 
  <county name="桐庐县" /> 
  <county name="淳安县" /> 
  <county name="建德市" /> 
  <county name="富阳市" /> 
  <county name="临安市" /> 
  </city>
 <city name="宁波市">
  <county name="市辖区" /> 
  <county name="海曙区" /> 
  <county name="江东区" /> 
  <county name="江北区" /> 
  <county name="北仑区" /> 
  <county name="镇海区" /> 
  <county name="鄞州区" /> 
  <county name="象山县" /> 
  <county name="宁海县" /> 
  <county name="余姚市" /> 
  <county name="慈溪市" /> 
  <county name="奉化市" /> 
  </city>
 <city name="温州市">
  <county name="市辖区" /> 
  <county name="鹿城区" /> 
  <county name="龙湾区" /> 
  <county name="瓯海区" /> 
  <county name="洞头县" /> 
  <county name="永嘉县" /> 
  <county name="平阳县" /> 
  <county name="苍南县" /> 
  <county name="文成县" /> 
  <county name="泰顺县" /> 
  <county name="瑞安市" /> 
  <county name="乐清市" /> 
  </city>
 <city name="嘉兴市">
  <county name="市辖区" /> 
  <county name="秀城区" /> 
  <county name="秀洲区" /> 
  <county name="嘉善县" /> 
  <county name="海盐县" /> 
  <county name="海宁市" /> 
  <county name="平湖市" /> 
  <county name="桐乡市" /> 
  </city>
 <city name="湖州市">
  <county name="市辖区" /> 
  <county name="吴兴区" /> 
  <county name="南浔区" /> 
  <county name="德清县" /> 
  <county name="长兴县" /> 
  <county name="安吉县" /> 
  </city>
 <city name="绍兴市">
  <county name="市辖区" /> 
  <county name="越城区" /> 
  <county name="绍兴县" /> 
  <county name="新昌县" /> 
  <county name="诸暨市" /> 
  <county name="上虞市" /> 
  <county name="嵊州市" /> 
  </city>
 <city name="金华市">
  <county name="市辖区" /> 
  <county name="婺城区" /> 
  <county name="金东区" /> 
  <county name="武义县" /> 
  <county name="浦江县" /> 
  <county name="磐安县" /> 
  <county name="兰溪市" /> 
  <county name="义乌市" /> 
  <county name="东阳市" /> 
  <county name="永康市" /> 
  </city>
 <city name="衢州市">
  <county name="市辖区" /> 
  <county name="柯城区" /> 
  <county name="衢江区" /> 
  <county name="常山县" /> 
  <county name="开化县" /> 
  <county name="龙游县" /> 
  <county name="江山市" /> 
  </city>
 <city name="舟山市">
  <county name="市辖区" /> 
  <county name="定海区" /> 
  <county name="普陀区" /> 
  <county name="岱山县" /> 
  <county name="嵊泗县" /> 
  </city>
 <city name="台州市">
  <county name="市辖区" /> 
  <county name="椒江区" /> 
  <county name="黄岩区" /> 
  <county name="路桥区" /> 
  <county name="玉环县" /> 
  <county name="三门县" /> 
  <county name="天台县" /> 
  <county name="仙居县" /> 
  <county name="温岭市" /> 
  <county name="临海市" /> 
  </city>
 <city name="丽水市">
  <county name="市辖区" /> 
  <county name="莲都区" /> 
  <county name="青田县" /> 
  <county name="缙云县" /> 
  <county name="遂昌县" /> 
  <county name="松阳县" /> 
  <county name="云和县" /> 
  <county name="庆元县" /> 
  <county name="景宁畲族自治县" /> 
  <county name="龙泉市" /> 
  </city>
  </province>
 <province name="安徽省">
 <city name="合肥市">
  <county name="市辖区" /> 
  <county name="瑶海区" /> 
  <county name="庐阳区" /> 
  <county name="蜀山区" /> 
  <county name="包河区" /> 
  <county name="长丰县" /> 
  <county name="肥东县" /> 
  <county name="肥西县" /> 
  </city>
 <city name="芜湖市">
  <county name="市辖区" /> 
  <county name="镜湖区" /> 
  <county name="马塘区" /> 
  <county name="新芜区" /> 
  <county name="鸠江区" /> 
  <county name="芜湖县" /> 
  <county name="繁昌县" /> 
  <county name="南陵县" /> 
  </city>
 <city name="蚌埠市">
  <county name="市辖区" /> 
  <county name="龙子湖区" /> 
  <county name="蚌山区" /> 
  <county name="禹会区" /> 
  <county name="淮上区" /> 
  <county name="怀远县" /> 
  <county name="五河县" /> 
  <county name="固镇县" /> 
  </city>
 <city name="淮南市">
  <county name="市辖区" /> 
  <county name="大通区" /> 
  <county name="田家庵区" /> 
  <county name="谢家集区" /> 
  <county name="八公山区" /> 
  <county name="潘集区" /> 
  <county name="凤台县" /> 
  </city>
 <city name="马鞍山市">
  <county name="市辖区" /> 
  <county name="金家庄区" /> 
  <county name="花山区" /> 
  <county name="雨山区" /> 
  <county name="当涂县" /> 
  </city>
 <city name="淮北市">
  <county name="市辖区" /> 
  <county name="杜集区" /> 
  <county name="相山区" /> 
  <county name="烈山区" /> 
  <county name="濉溪县" /> 
  </city>
 <city name="铜陵市">
  <county name="市辖区" /> 
  <county name="铜官山区" /> 
  <county name="狮子山区" /> 
  <county name="郊区" /> 
  <county name="铜陵县" /> 
  </city>
 <city name="安庆市">
  <county name="市辖区" /> 
  <county name="迎江区" /> 
  <county name="大观区" /> 
  <county name="郊区" /> 
  <county name="怀宁县" /> 
  <county name="枞阳县" /> 
  <county name="潜山县" /> 
  <county name="太湖县" /> 
  <county name="宿松县" /> 
  <county name="望江县" /> 
  <county name="岳西县" /> 
  <county name="桐城市" /> 
  </city>
 <city name="黄山市">
  <county name="市辖区" /> 
  <county name="屯溪区" /> 
  <county name="黄山区" /> 
  <county name="徽州区" /> 
  <county name="歙县" /> 
  <county name="休宁县" /> 
  <county name="黟县" /> 
  <county name="祁门县" /> 
  </city>
 <city name="滁州市">
  <county name="市辖区" /> 
  <county name="琅琊区" /> 
  <county name="南谯区" /> 
  <county name="来安县" /> 
  <county name="全椒县" /> 
  <county name="定远县" /> 
  <county name="凤阳县" /> 
  <county name="天长市" /> 
  <county name="明光市" /> 
  </city>
 <city name="阜阳市">
  <county name="市辖区" /> 
  <county name="颍州区" /> 
  <county name="颍东区" /> 
  <county name="颍泉区" /> 
  <county name="临泉县" /> 
  <county name="太和县" /> 
  <county name="阜南县" /> 
  <county name="颍上县" /> 
  <county name="界首市" /> 
  </city>
 <city name="宿州市">
  <county name="市辖区" /> 
  <county name="墉桥区" /> 
  <county name="砀山县" /> 
  <county name="萧县" /> 
  <county name="灵璧县" /> 
  <county name="泗县" /> 
  </city>
 <city name="巢湖市">
  <county name="市辖区" /> 
  <county name="居巢区" /> 
  <county name="庐江县" /> 
  <county name="无为县" /> 
  <county name="含山县" /> 
  <county name="和县" /> 
  </city>
 <city name="六安市">
  <county name="市辖区" /> 
  <county name="金安区" /> 
  <county name="裕安区" /> 
  <county name="寿县" /> 
  <county name="霍邱县" /> 
  <county name="舒城县" /> 
  <county name="金寨县" /> 
  <county name="霍山县" /> 
  </city>
 <city name="亳州市">
  <county name="市辖区" /> 
  <county name="谯城区" /> 
  <county name="涡阳县" /> 
  <county name="蒙城县" /> 
  <county name="利辛县" /> 
  </city>
 <city name="池州市">
  <county name="市辖区" /> 
  <county name="贵池区" /> 
  <county name="东至县" /> 
  <county name="石台县" /> 
  <county name="青阳县" /> 
  </city>
 <city name="宣城市">
  <county name="市辖区" /> 
  <county name="宣州区" /> 
  <county name="郎溪县" /> 
  <county name="广德县" /> 
  <county name="泾县" /> 
  <county name="绩溪县" /> 
  <county name="旌德县" /> 
  <county name="宁国市" /> 
  </city>
  </province>
 <province name="福建省">
 <city name="福州市">
  <county name="市辖区" /> 
  <county name="鼓楼区" /> 
  <county name="台江区" /> 
  <county name="仓山区" /> 
  <county name="马尾区" /> 
  <county name="晋安区" /> 
  <county name="闽侯县" /> 
  <county name="连江县" /> 
  <county name="罗源县" /> 
  <county name="闽清县" /> 
  <county name="永泰县" /> 
  <county name="平潭县" /> 
  <county name="福清市" /> 
  <county name="长乐市" /> 
  </city>
 <city name="厦门市">
  <county name="市辖区" /> 
  <county name="思明区" /> 
  <county name="海沧区" /> 
  <county name="湖里区" /> 
  <county name="集美区" /> 
  <county name="同安区" /> 
  <county name="翔安区" /> 
  </city>
 <city name="莆田市">
  <county name="市辖区" /> 
  <county name="城厢区" /> 
  <county name="涵江区" /> 
  <county name="荔城区" /> 
  <county name="秀屿区" /> 
  <county name="仙游县" /> 
  </city>
 <city name="三明市">
  <county name="市辖区" /> 
  <county name="梅列区" /> 
  <county name="三元区" /> 
  <county name="明溪县" /> 
  <county name="清流县" /> 
  <county name="宁化县" /> 
  <county name="大田县" /> 
  <county name="尤溪县" /> 
  <county name="沙县" /> 
  <county name="将乐县" /> 
  <county name="泰宁县" /> 
  <county name="建宁县" /> 
  <county name="永安市" /> 
  </city>
 <city name="泉州市">
  <county name="市辖区" /> 
  <county name="鲤城区" /> 
  <county name="丰泽区" /> 
  <county name="洛江区" /> 
  <county name="泉港区" /> 
  <county name="惠安县" /> 
  <county name="安溪县" /> 
  <county name="永春县" /> 
  <county name="德化县" /> 
  <county name="金门县" /> 
  <county name="石狮市" /> 
  <county name="晋江市" /> 
  <county name="南安市" /> 
  </city>
 <city name="漳州市">
  <county name="市辖区" /> 
  <county name="芗城区" /> 
  <county name="龙文区" /> 
  <county name="云霄县" /> 
  <county name="漳浦县" /> 
  <county name="诏安县" /> 
  <county name="长泰县" /> 
  <county name="东山县" /> 
  <county name="南靖县" /> 
  <county name="平和县" /> 
  <county name="华安县" /> 
  <county name="龙海市" /> 
  </city>
 <city name="南平市">
  <county name="市辖区" /> 
  <county name="延平区" /> 
  <county name="顺昌县" /> 
  <county name="浦城县" /> 
  <county name="光泽县" /> 
  <county name="松溪县" /> 
  <county name="政和县" /> 
  <county name="邵武市" /> 
  <county name="武夷山市" /> 
  <county name="建瓯市" /> 
  <county name="建阳市" /> 
  </city>
 <city name="龙岩市">
  <county name="市辖区" /> 
  <county name="新罗区" /> 
  <county name="长汀县" /> 
  <county name="永定县" /> 
  <county name="上杭县" /> 
  <county name="武平县" /> 
  <county name="连城县" /> 
  <county name="漳平市" /> 
  </city>
 <city name="宁德市">
  <county name="市辖区" /> 
  <county name="蕉城区" /> 
  <county name="霞浦县" /> 
  <county name="古田县" /> 
  <county name="屏南县" /> 
  <county name="寿宁县" /> 
  <county name="周宁县" /> 
  <county name="柘荣县" /> 
  <county name="福安市" /> 
  <county name="福鼎市" /> 
  </city>
  </province>
 <province name="江西省">
 <city name="南昌市">
  <county name="市辖区" /> 
  <county name="东湖区" /> 
  <county name="西湖区" /> 
  <county name="青云谱区" /> 
  <county name="湾里区" /> 
  <county name="青山湖区" /> 
  <county name="南昌县" /> 
  <county name="新建县" /> 
  <county name="安义县" /> 
  <county name="进贤县" /> 
  </city>
 <city name="景德镇市">
  <county name="市辖区" /> 
  <county name="昌江区" /> 
  <county name="珠山区" /> 
  <county name="浮梁县" /> 
  <county name="乐平市" /> 
  </city>
 <city name="萍乡市">
  <county name="市辖区" /> 
  <county name="安源区" /> 
  <county name="湘东区" /> 
  <county name="莲花县" /> 
  <county name="上栗县" /> 
  <county name="芦溪县" /> 
  </city>
 <city name="九江市">
  <county name="市辖区" /> 
  <county name="庐山区" /> 
  <county name="浔阳区" /> 
  <county name="九江县" /> 
  <county name="武宁县" /> 
  <county name="修水县" /> 
  <county name="永修县" /> 
  <county name="德安县" /> 
  <county name="星子县" /> 
  <county name="都昌县" /> 
  <county name="湖口县" /> 
  <county name="彭泽县" /> 
  <county name="瑞昌市" /> 
  </city>
 <city name="新余市">
  <county name="市辖区" /> 
  <county name="渝水区" /> 
  <county name="分宜县" /> 
  </city>
 <city name="鹰潭市">
  <county name="市辖区" /> 
  <county name="月湖区" /> 
  <county name="余江县" /> 
  <county name="贵溪市" /> 
  </city>
 <city name="赣州市">
  <county name="市辖区" /> 
  <county name="章贡区" /> 
  <county name="赣县" /> 
  <county name="信丰县" /> 
  <county name="大余县" /> 
  <county name="上犹县" /> 
  <county name="崇义县" /> 
  <county name="安远县" /> 
  <county name="龙南县" /> 
  <county name="定南县" /> 
  <county name="全南县" /> 
  <county name="宁都县" /> 
  <county name="于都县" /> 
  <county name="兴国县" /> 
  <county name="会昌县" /> 
  <county name="寻乌县" /> 
  <county name="石城县" /> 
  <county name="瑞金市" /> 
  <county name="南康市" /> 
  </city>
 <city name="吉安市">
  <county name="市辖区" /> 
  <county name="吉州区" /> 
  <county name="青原区" /> 
  <county name="吉安县" /> 
  <county name="吉水县" /> 
  <county name="峡江县" /> 
  <county name="新干县" /> 
  <county name="永丰县" /> 
  <county name="泰和县" /> 
  <county name="遂川县" /> 
  <county name="万安县" /> 
  <county name="安福县" /> 
  <county name="永新县" /> 
  <county name="井冈山市" /> 
  </city>
 <city name="宜春市">
  <county name="市辖区" /> 
  <county name="袁州区" /> 
  <county name="奉新县" /> 
  <county name="万载县" /> 
  <county name="上高县" /> 
  <county name="宜丰县" /> 
  <county name="靖安县" /> 
  <county name="铜鼓县" /> 
  <county name="丰城市" /> 
  <county name="樟树市" /> 
  <county name="高安市" /> 
  </city>
 <city name="抚州市">
  <county name="市辖区" /> 
  <county name="临川区" /> 
  <county name="南城县" /> 
  <county name="黎川县" /> 
  <county name="南丰县" /> 
  <county name="崇仁县" /> 
  <county name="乐安县" /> 
  <county name="宜黄县" /> 
  <county name="金溪县" /> 
  <county name="资溪县" /> 
  <county name="东乡县" /> 
  <county name="广昌县" /> 
  </city>
 <city name="上饶市">
  <county name="市辖区" /> 
  <county name="信州区" /> 
  <county name="上饶县" /> 
  <county name="广丰县" /> 
  <county name="玉山县" /> 
  <county name="铅山县" /> 
  <county name="横峰县" /> 
  <county name="弋阳县" /> 
  <county name="余干县" /> 
  <county name="鄱阳县" /> 
  <county name="万年县" /> 
  <county name="婺源县" /> 
  <county name="德兴市" /> 
  </city>
  </province>
 <province name="山东省">
 <city name="济南市">
  <county name="市辖区" /> 
  <county name="历下区" /> 
  <county name="市中区" /> 
  <county name="槐荫区" /> 
  <county name="天桥区" /> 
  <county name="历城区" /> 
  <county name="长清区" /> 
  <county name="平阴县" /> 
  <county name="济阳县" /> 
  <county name="商河县" /> 
  <county name="章丘市" /> 
  </city>
 <city name="青岛市">
  <county name="市辖区" /> 
  <county name="市南区" /> 
  <county name="市北区" /> 
  <county name="四方区" /> 
  <county name="黄岛区" /> 
  <county name="崂山区" /> 
  <county name="李沧区" /> 
  <county name="城阳区" /> 
  <county name="胶州市" /> 
  <county name="即墨市" /> 
  <county name="平度市" /> 
  <county name="胶南市" /> 
  <county name="莱西市" /> 
  </city>
 <city name="淄博市">
  <county name="市辖区" /> 
  <county name="淄川区" /> 
  <county name="张店区" /> 
  <county name="博山区" /> 
  <county name="临淄区" /> 
  <county name="周村区" /> 
  <county name="桓台县" /> 
  <county name="高青县" /> 
  <county name="沂源县" /> 
  </city>
 <city name="枣庄市">
  <county name="市辖区" /> 
  <county name="市中区" /> 
  <county name="薛城区" /> 
  <county name="峄城区" /> 
  <county name="台儿庄区" /> 
  <county name="山亭区" /> 
  <county name="滕州市" /> 
  </city>
 <city name="东营市">
  <county name="市辖区" /> 
  <county name="东营区" /> 
  <county name="河口区" /> 
  <county name="垦利县" /> 
  <county name="利津县" /> 
  <county name="广饶县" /> 
  </city>
 <city name="烟台市">
  <county name="市辖区" /> 
  <county name="芝罘区" /> 
  <county name="福山区" /> 
  <county name="牟平区" /> 
  <county name="莱山区" /> 
  <county name="长岛县" /> 
  <county name="龙口市" /> 
  <county name="莱阳市" /> 
  <county name="莱州市" /> 
  <county name="蓬莱市" /> 
  <county name="招远市" /> 
  <county name="栖霞市" /> 
  <county name="海阳市" /> 
  </city>
 <city name="潍坊市">
  <county name="市辖区" /> 
  <county name="潍城区" /> 
  <county name="寒亭区" /> 
  <county name="坊子区" /> 
  <county name="奎文区" /> 
  <county name="临朐县" /> 
  <county name="昌乐县" /> 
  <county name="青州市" /> 
  <county name="诸城市" /> 
  <county name="寿光市" /> 
  <county name="安丘市" /> 
  <county name="高密市" /> 
  <county name="昌邑市" /> 
  </city>
 <city name="济宁市">
  <county name="市辖区" /> 
  <county name="市中区" /> 
  <county name="任城区" /> 
  <county name="微山县" /> 
  <county name="鱼台县" /> 
  <county name="金乡县" /> 
  <county name="嘉祥县" /> 
  <county name="汶上县" /> 
  <county name="泗水县" /> 
  <county name="梁山县" /> 
  <county name="曲阜市" /> 
  <county name="兖州市" /> 
  <county name="邹城市" /> 
  </city>
 <city name="泰安市">
  <county name="市辖区" /> 
  <county name="泰山区" /> 
  <county name="岱岳区" /> 
  <county name="宁阳县" /> 
  <county name="东平县" /> 
  <county name="新泰市" /> 
  <county name="肥城市" /> 
  </city>
 <city name="威海市">
  <county name="市辖区" /> 
  <county name="环翠区" /> 
  <county name="文登市" /> 
  <county name="荣成市" /> 
  <county name="乳山市" /> 
  </city>
 <city name="日照市">
  <county name="市辖区" /> 
  <county name="东港区" /> 
  <county name="岚山区" /> 
  <county name="五莲县" /> 
  <county name="莒县" /> 
  </city>
 <city name="莱芜市">
  <county name="市辖区" /> 
  <county name="莱城区" /> 
  <county name="钢城区" /> 
  </city>
 <city name="临沂市">
  <county name="市辖区" /> 
  <county name="兰山区" /> 
  <county name="罗庄区" /> 
  <county name="河东区" /> 
  <county name="沂南县" /> 
  <county name="郯城县" /> 
  <county name="沂水县" /> 
  <county name="苍山县" /> 
  <county name="费县" /> 
  <county name="平邑县" /> 
  <county name="莒南县" /> 
  <county name="蒙阴县" /> 
  <county name="临沭县" /> 
  </city>
 <city name="德州市">
  <county name="市辖区" /> 
  <county name="德城区" /> 
  <county name="陵县" /> 
  <county name="宁津县" /> 
  <county name="庆云县" /> 
  <county name="临邑县" /> 
  <county name="齐河县" /> 
  <county name="平原县" /> 
  <county name="夏津县" /> 
  <county name="武城县" /> 
  <county name="乐陵市" /> 
  <county name="禹城市" /> 
  </city>
 <city name="聊城市">
  <county name="市辖区" /> 
  <county name="东昌府区" /> 
  <county name="阳谷县" /> 
  <county name="莘县" /> 
  <county name="茌平县" /> 
  <county name="东阿县" /> 
  <county name="冠县" /> 
  <county name="高唐县" /> 
  <county name="临清市" /> 
  </city>
 <city name="滨州市">
  <county name="市辖区" /> 
  <county name="滨城区" /> 
  <county name="惠民县" /> 
  <county name="阳信县" /> 
  <county name="无棣县" /> 
  <county name="沾化县" /> 
  <county name="博兴县" /> 
  <county name="邹平县" /> 
  </city>
 <city name="荷泽市">
  <county name="市辖区" /> 
  <county name="牡丹区" /> 
  <county name="曹县" /> 
  <county name="单县" /> 
  <county name="成武县" /> 
  <county name="巨野县" /> 
  <county name="郓城县" /> 
  <county name="鄄城县" /> 
  <county name="定陶县" /> 
  <county name="东明县" /> 
  </city>
  </province>
 <province name="河南省">
 <city name="郑州市">
  <county name="市辖区" /> 
  <county name="中原区" /> 
  <county name="二七区" /> 
  <county name="管城回族区" /> 
  <county name="金水区" /> 
  <county name="上街区" /> 
  <county name="惠济区" /> 
  <county name="中牟县" /> 
  <county name="巩义市" /> 
  <county name="荥阳市" /> 
  <county name="新密市" /> 
  <county name="新郑市" /> 
  <county name="登封市" /> 
  </city>
 <city name="开封市">
  <county name="市辖区" /> 
  <county name="龙亭区" /> 
  <county name="顺河回族区" /> 
  <county name="鼓楼区" /> 
  <county name="南关区" /> 
  <county name="郊区" /> 
  <county name="杞县" /> 
  <county name="通许县" /> 
  <county name="尉氏县" /> 
  <county name="开封县" /> 
  <county name="兰考县" /> 
  </city>
 <city name="洛阳市">
  <county name="市辖区" /> 
  <county name="老城区" /> 
  <county name="西工区" /> 
  <county name="廛河回族区" /> 
  <county name="涧西区" /> 
  <county name="吉利区" /> 
  <county name="洛龙区" /> 
  <county name="孟津县" /> 
  <county name="新安县" /> 
  <county name="栾川县" /> 
  <county name="嵩县" /> 
  <county name="汝阳县" /> 
  <county name="宜阳县" /> 
  <county name="洛宁县" /> 
  <county name="伊川县" /> 
  <county name="偃师市" /> 
  </city>
 <city name="平顶山市">
  <county name="市辖区" /> 
  <county name="新华区" /> 
  <county name="卫东区" /> 
  <county name="石龙区" /> 
  <county name="湛河区" /> 
  <county name="宝丰县" /> 
  <county name="叶县" /> 
  <county name="鲁山县" /> 
  <county name="郏县" /> 
  <county name="舞钢市" /> 
  <county name="汝州市" /> 
  </city>
 <city name="安阳市">
  <county name="市辖区" /> 
  <county name="文峰区" /> 
  <county name="北关区" /> 
  <county name="殷都区" /> 
  <county name="龙安区" /> 
  <county name="安阳县" /> 
  <county name="汤阴县" /> 
  <county name="滑县" /> 
  <county name="内黄县" /> 
  <county name="林州市" /> 
  </city>
 <city name="鹤壁市">
  <county name="市辖区" /> 
  <county name="鹤山区" /> 
  <county name="山城区" /> 
  <county name="淇滨区" /> 
  <county name="浚县" /> 
  <county name="淇县" /> 
  </city>
 <city name="新乡市">
  <county name="市辖区" /> 
  <county name="红旗区" /> 
  <county name="卫滨区" /> 
  <county name="凤泉区" /> 
  <county name="牧野区" /> 
  <county name="新乡县" /> 
  <county name="获嘉县" /> 
  <county name="原阳县" /> 
  <county name="延津县" /> 
  <county name="封丘县" /> 
  <county name="长垣县" /> 
  <county name="卫辉市" /> 
  <county name="辉县市" /> 
  </city>
 <city name="焦作市">
  <county name="市辖区" /> 
  <county name="解放区" /> 
  <county name="中站区" /> 
  <county name="马村区" /> 
  <county name="山阳区" /> 
  <county name="修武县" /> 
  <county name="博爱县" /> 
  <county name="武陟县" /> 
  <county name="温县" /> 
  <county name="济源市" /> 
  <county name="沁阳市" /> 
  <county name="孟州市" /> 
  </city>
 <city name="濮阳市">
  <county name="市辖区" /> 
  <county name="华龙区" /> 
  <county name="清丰县" /> 
  <county name="南乐县" /> 
  <county name="范县" /> 
  <county name="台前县" /> 
  <county name="濮阳县" /> 
  </city>
 <city name="许昌市">
  <county name="市辖区" /> 
  <county name="魏都区" /> 
  <county name="许昌县" /> 
  <county name="鄢陵县" /> 
  <county name="襄城县" /> 
  <county name="禹州市" /> 
  <county name="长葛市" /> 
  </city>
 <city name="漯河市">
  <county name="市辖区" /> 
  <county name="源汇区" /> 
  <county name="郾城区" /> 
  <county name="召陵区" /> 
  <county name="舞阳县" /> 
  <county name="临颍县" /> 
  </city>
 <city name="三门峡市">
  <county name="市辖区" /> 
  <county name="湖滨区" /> 
  <county name="渑池县" /> 
  <county name="陕县" /> 
  <county name="卢氏县" /> 
  <county name="义马市" /> 
  <county name="灵宝市" /> 
  </city>
 <city name="南阳市">
  <county name="市辖区" /> 
  <county name="宛城区" /> 
  <county name="卧龙区" /> 
  <county name="南召县" /> 
  <county name="方城县" /> 
  <county name="西峡县" /> 
  <county name="镇平县" /> 
  <county name="内乡县" /> 
  <county name="淅川县" /> 
  <county name="社旗县" /> 
  <county name="唐河县" /> 
  <county name="新野县" /> 
  <county name="桐柏县" /> 
  <county name="邓州市" /> 
  </city>
 <city name="商丘市">
  <county name="市辖区" /> 
  <county name="梁园区" /> 
  <county name="睢阳区" /> 
  <county name="民权县" /> 
  <county name="睢县" /> 
  <county name="宁陵县" /> 
  <county name="柘城县" /> 
  <county name="虞城县" /> 
  <county name="夏邑县" /> 
  <county name="永城市" /> 
  </city>
 <city name="信阳市">
  <county name="市辖区" /> 
  <county name="师河区" /> 
  <county name="平桥区" /> 
  <county name="罗山县" /> 
  <county name="光山县" /> 
  <county name="新县" /> 
  <county name="商城县" /> 
  <county name="固始县" /> 
  <county name="潢川县" /> 
  <county name="淮滨县" /> 
  <county name="息县" /> 
  </city>
 <city name="周口市">
  <county name="市辖区" /> 
  <county name="川汇区" /> 
  <county name="扶沟县" /> 
  <county name="西华县" /> 
  <county name="商水县" /> 
  <county name="沈丘县" /> 
  <county name="郸城县" /> 
  <county name="淮阳县" /> 
  <county name="太康县" /> 
  <county name="鹿邑县" /> 
  <county name="项城市" /> 
  </city>
 <city name="驻马店市">
  <county name="市辖区" /> 
  <county name="驿城区" /> 
  <county name="西平县" /> 
  <county name="上蔡县" /> 
  <county name="平舆县" /> 
  <county name="正阳县" /> 
  <county name="确山县" /> 
  <county name="泌阳县" /> 
  <county name="汝南县" /> 
  <county name="遂平县" /> 
  <county name="新蔡县" /> 
  </city>
  </province>
 <province name="湖北省">
 <city name="武汉市">
  <county name="市辖区" /> 
  <county name="江岸区" /> 
  <county name="江汉区" /> 
  <county name="乔口区" /> 
  <county name="汉阳区" /> 
  <county name="武昌区" /> 
  <county name="青山区" /> 
  <county name="洪山区" /> 
  <county name="东西湖区" /> 
  <county name="汉南区" /> 
  <county name="蔡甸区" /> 
  <county name="江夏区" /> 
  <county name="黄陂区" /> 
  <county name="新洲区" /> 
  </city>
 <city name="黄石市">
  <county name="市辖区" /> 
  <county name="黄石港区" /> 
  <county name="西塞山区" /> 
  <county name="下陆区" /> 
  <county name="铁山区" /> 
  <county name="阳新县" /> 
  <county name="大冶市" /> 
  </city>
 <city name="十堰市">
  <county name="市辖区" /> 
  <county name="茅箭区" /> 
  <county name="张湾区" /> 
  <county name="郧县" /> 
  <county name="郧西县" /> 
  <county name="竹山县" /> 
  <county name="竹溪县" /> 
  <county name="房县" /> 
  <county name="丹江口市" /> 
  </city>
 <city name="宜昌市">
  <county name="市辖区" /> 
  <county name="西陵区" /> 
  <county name="伍家岗区" /> 
  <county name="点军区" /> 
  <county name="猇亭区" /> 
  <county name="夷陵区" /> 
  <county name="远安县" /> 
  <county name="兴山县" /> 
  <county name="秭归县" /> 
  <county name="长阳土家族自治县" /> 
  <county name="五峰土家族自治县" /> 
  <county name="宜都市" /> 
  <county name="当阳市" /> 
  <county name="枝江市" /> 
  </city>
 <city name="襄阳市">
  <county name="市辖区" /> 
  <county name="襄城区" /> 
  <county name="樊城区" /> 
  <county name="襄阳区" /> 
  <county name="南漳县" /> 
  <county name="谷城县" /> 
  <county name="保康县" /> 
  <county name="老河口市" /> 
  <county name="枣阳市" /> 
  <county name="宜城市" /> 
  </city>
 <city name="鄂州市">
  <county name="市辖区" /> 
  <county name="梁子湖区" /> 
  <county name="华容区" /> 
  <county name="鄂城区" /> 
  </city>
 <city name="荆门市">
  <county name="市辖区" /> 
  <county name="东宝区" /> 
  <county name="掇刀区" /> 
  <county name="京山县" /> 
  <county name="沙洋县" /> 
  <county name="钟祥市" /> 
  </city>
 <city name="孝感市">
  <county name="市辖区" /> 
  <county name="孝南区" /> 
  <county name="孝昌县" /> 
  <county name="大悟县" /> 
  <county name="云梦县" /> 
  <county name="应城市" /> 
  <county name="安陆市" /> 
  <county name="汉川市" /> 
  </city>
 <city name="荆州市">
  <county name="市辖区" /> 
  <county name="沙市区" /> 
  <county name="荆州区" /> 
  <county name="公安县" /> 
  <county name="监利县" /> 
  <county name="江陵县" /> 
  <county name="石首市" /> 
  <county name="洪湖市" /> 
  <county name="松滋市" /> 
  </city>
 <city name="黄冈市">
  <county name="市辖区" /> 
  <county name="黄州区" /> 
  <county name="团风县" /> 
  <county name="红安县" /> 
  <county name="罗田县" /> 
  <county name="英山县" /> 
  <county name="浠水县" /> 
  <county name="蕲春县" /> 
  <county name="黄梅县" /> 
  <county name="麻城市" /> 
  <county name="武穴市" /> 
  </city>
 <city name="咸宁市">
  <county name="市辖区" /> 
  <county name="咸安区" /> 
  <county name="嘉鱼县" /> 
  <county name="通城县" /> 
  <county name="崇阳县" /> 
  <county name="通山县" /> 
  <county name="赤壁市" /> 
  </city>
 <city name="随州市">
  <county name="市辖区" /> 
  <county name="曾都区" /> 
  <county name="广水市" /> 
  </city>
 <city name="恩施自治州">
  <county name="恩施市" /> 
  <county name="利川市" /> 
  <county name="建始县" /> 
  <county name="巴东县" /> 
  <county name="宣恩县" /> 
  <county name="咸丰县" /> 
  <county name="来凤县" /> 
  <county name="鹤峰县" /> 
  </city>
 <city name="湖北省辖单位">
  <county name="仙桃市" /> 
  <county name="潜江市" /> 
  <county name="天门市" /> 
  <county name="神农架林区" /> 
  </city>
  </province>
 <province name="湖南省">
 <city name="长沙市">
  <county name="市辖区" /> 
  <county name="芙蓉区" /> 
  <county name="天心区" /> 
  <county name="岳麓区" /> 
  <county name="开福区" /> 
  <county name="雨花区" /> 
  <county name="长沙县" /> 
  <county name="望城县" /> 
  <county name="宁乡县" /> 
  <county name="浏阳市" /> 
  </city>
 <city name="株洲市">
  <county name="市辖区" /> 
  <county name="荷塘区" /> 
  <county name="芦淞区" /> 
  <county name="石峰区" /> 
  <county name="天元区" /> 
  <county name="株洲县" /> 
  <county name="攸县" /> 
  <county name="茶陵县" /> 
  <county name="炎陵县" /> 
  <county name="醴陵市" /> 
  </city>
 <city name="湘潭市">
  <county name="市辖区" /> 
  <county name="雨湖区" /> 
  <county name="岳塘区" /> 
  <county name="湘潭县" /> 
  <county name="湘乡市" /> 
  <county name="韶山市" /> 
  </city>
 <city name="衡阳市">
  <county name="市辖区" /> 
  <county name="珠晖区" /> 
  <county name="雁峰区" /> 
  <county name="石鼓区" /> 
  <county name="蒸湘区" /> 
  <county name="南岳区" /> 
  <county name="衡阳县" /> 
  <county name="衡南县" /> 
  <county name="衡山县" /> 
  <county name="衡东县" /> 
  <county name="祁东县" /> 
  <county name="耒阳市" /> 
  <county name="常宁市" /> 
  </city>
 <city name="邵阳市">
  <county name="市辖区" /> 
  <county name="双清区" /> 
  <county name="大祥区" /> 
  <county name="北塔区" /> 
  <county name="邵东县" /> 
  <county name="新邵县" /> 
  <county name="邵阳县" /> 
  <county name="隆回县" /> 
  <county name="洞口县" /> 
  <county name="绥宁县" /> 
  <county name="新宁县" /> 
  <county name="城步苗族自治县" /> 
  <county name="武冈市" /> 
  </city>
 <city name="岳阳市">
  <county name="市辖区" /> 
  <county name="岳阳楼区" /> 
  <county name="云溪区" /> 
  <county name="君山区" /> 
  <county name="岳阳县" /> 
  <county name="华容县" /> 
  <county name="湘阴县" /> 
  <county name="平江县" /> 
  <county name="汨罗市" /> 
  <county name="临湘市" /> 
  </city>
 <city name="常德市">
  <county name="市辖区" /> 
  <county name="武陵区" /> 
  <county name="鼎城区" /> 
  <county name="安乡县" /> 
  <county name="汉寿县" /> 
  <county name="澧县" /> 
  <county name="临澧县" /> 
  <county name="桃源县" /> 
  <county name="石门县" /> 
  <county name="津市市" /> 
  </city>
 <city name="张家界市">
  <county name="市辖区" /> 
  <county name="永定区" /> 
  <county name="武陵源区" /> 
  <county name="慈利县" /> 
  <county name="桑植县" /> 
  </city>
 <city name="益阳市">
  <county name="市辖区" /> 
  <county name="资阳区" /> 
  <county name="赫山区" /> 
  <county name="南县" /> 
  <county name="桃江县" /> 
  <county name="安化县" /> 
  <county name="沅江市" /> 
  </city>
 <city name="郴州市">
  <county name="市辖区" /> 
  <county name="北湖区" /> 
  <county name="苏仙区" /> 
  <county name="桂阳县" /> 
  <county name="宜章县" /> 
  <county name="永兴县" /> 
  <county name="嘉禾县" /> 
  <county name="临武县" /> 
  <county name="汝城县" /> 
  <county name="桂东县" /> 
  <county name="安仁县" /> 
  <county name="资兴市" /> 
  </city>
 <city name="永州市">
  <county name="市辖区" /> 
  <county name="芝山区" /> 
  <county name="冷水滩区" /> 
  <county name="祁阳县" /> 
  <county name="东安县" /> 
  <county name="双牌县" /> 
  <county name="道县" /> 
  <county name="江永县" /> 
  <county name="宁远县" /> 
  <county name="蓝山县" /> 
  <county name="新田县" /> 
  <county name="江华瑶族自治县" /> 
  </city>
 <city name="怀化市">
  <county name="市辖区" /> 
  <county name="鹤城区" /> 
  <county name="中方县" /> 
  <county name="沅陵县" /> 
  <county name="辰溪县" /> 
  <county name="溆浦县" /> 
  <county name="会同县" /> 
  <county name="麻阳苗族自治县" /> 
  <county name="新晃侗族自治县" /> 
  <county name="芷江侗族自治县" /> 
  <county name="靖州苗族侗族自治县" /> 
  <county name="通道侗族自治县" /> 
  <county name="洪江市" /> 
  </city>
 <city name="娄底市">
  <county name="市辖区" /> 
  <county name="娄星区" /> 
  <county name="双峰县" /> 
  <county name="新化县" /> 
  <county name="冷水江市" /> 
  <county name="涟源市" /> 
  </city>
 <city name="湘西自治州">
  <county name="吉首市" /> 
  <county name="泸溪县" /> 
  <county name="凤凰县" /> 
  <county name="花垣县" /> 
  <county name="保靖县" /> 
  <county name="古丈县" /> 
  <county name="永顺县" /> 
  <county name="龙山县" /> 
  </city>
  </province>
 <province name="广东省">
 <city name="广州市">
  <county name="市辖区" /> 
  <county name="东山区" /> 
  <county name="荔湾区" /> 
  <county name="越秀区" /> 
  <county name="海珠区" /> 
  <county name="天河区" /> 
  <county name="芳村区" /> 
  <county name="白云区" /> 
  <county name="黄埔区" /> 
  <county name="番禺区" /> 
  <county name="花都区" /> 
  <county name="增城市" /> 
  <county name="从化市" /> 
  </city>
 <city name="韶关市">
  <county name="市辖区" /> 
  <county name="武江区" /> 
  <county name="浈江区" /> 
  <county name="曲江区" /> 
  <county name="始兴县" /> 
  <county name="仁化县" /> 
  <county name="翁源县" /> 
  <county name="乳源瑶族自治县" /> 
  <county name="新丰县" /> 
  <county name="乐昌市" /> 
  <county name="南雄市" /> 
  </city>
 <city name="深圳市">
  <county name="市辖区" /> 
  <county name="罗湖区" /> 
  <county name="福田区" /> 
  <county name="南山区" /> 
  <county name="宝安区" /> 
  <county name="龙岗区" /> 
  <county name="盐田区" /> 
  <county name="龙华新区" /> 
  </city>
 <city name="珠海市">
  <county name="市辖区" /> 
  <county name="香洲区" /> 
  <county name="斗门区" /> 
  <county name="金湾区" /> 
  </city>
 <city name="汕头市">
  <county name="市辖区" /> 
  <county name="龙湖区" /> 
  <county name="金平区" /> 
  <county name="濠江区" /> 
  <county name="潮阳区" /> 
  <county name="潮南区" /> 
  <county name="澄海区" /> 
  <county name="南澳县" /> 
  </city>
 <city name="佛山市">
  <county name="市辖区" /> 
  <county name="禅城区" /> 
  <county name="南海区" /> 
  <county name="顺德区" /> 
  <county name="三水区" /> 
  <county name="高明区" /> 
  </city>
 <city name="江门市">
  <county name="市辖区" /> 
  <county name="蓬江区" /> 
  <county name="江海区" /> 
  <county name="新会区" /> 
  <county name="台山市" /> 
  <county name="开平市" /> 
  <county name="鹤山市" /> 
  <county name="恩平市" /> 
  </city>
 <city name="湛江市">
  <county name="市辖区" /> 
  <county name="赤坎区" /> 
  <county name="霞山区" /> 
  <county name="坡头区" /> 
  <county name="麻章区" /> 
  <county name="遂溪县" /> 
  <county name="徐闻县" /> 
  <county name="廉江市" /> 
  <county name="雷州市" /> 
  <county name="吴川市" /> 
  </city>
 <city name="茂名市">
  <county name="市辖区" /> 
  <county name="茂南区" /> 
  <county name="茂港区" /> 
  <county name="电白县" /> 
  <county name="高州市" /> 
  <county name="化州市" /> 
  <county name="信宜市" /> 
  </city>
 <city name="肇庆市">
  <county name="市辖区" /> 
  <county name="端州区" /> 
  <county name="鼎湖区" /> 
  <county name="广宁县" /> 
  <county name="怀集县" /> 
  <county name="封开县" /> 
  <county name="德庆县" /> 
  <county name="高要市" /> 
  <county name="四会市" /> 
  </city>
 <city name="惠州市">
  <county name="市辖区" /> 
  <county name="惠城区" /> 
  <county name="惠阳区" /> 
  <county name="博罗县" /> 
  <county name="惠东县" /> 
  <county name="龙门县" /> 
  </city>
 <city name="梅州市">
  <county name="市辖区" /> 
  <county name="梅江区" /> 
  <county name="梅县" /> 
  <county name="大埔县" /> 
  <county name="丰顺县" /> 
  <county name="五华县" /> 
  <county name="平远县" /> 
  <county name="蕉岭县" /> 
  <county name="兴宁市" /> 
  </city>
 <city name="汕尾市">
  <county name="市辖区" /> 
  <county name="城区" /> 
  <county name="海丰县" /> 
  <county name="陆河县" /> 
  <county name="陆丰市" /> 
  </city>
 <city name="河源市">
  <county name="市辖区" /> 
  <county name="源城区" /> 
  <county name="紫金县" /> 
  <county name="龙川县" /> 
  <county name="连平县" /> 
  <county name="和平县" /> 
  <county name="东源县" /> 
  </city>
 <city name="阳江市">
  <county name="市辖区" /> 
  <county name="江城区" /> 
  <county name="阳西县" /> 
  <county name="阳东县" /> 
  <county name="阳春市" /> 
  </city>
 <city name="清远市">
  <county name="市辖区" /> 
  <county name="清城区" /> 
  <county name="佛冈县" /> 
  <county name="阳山县" /> 
  <county name="连山壮族瑶族自治县" /> 
  <county name="连南瑶族自治县" /> 
  <county name="清新县" /> 
  <county name="英德市" /> 
  <county name="连州市" /> 
  </city>
  <city name="东莞市" >
  <county name="莞城"/>
  <county name="南城"/>
  <county name="东城"/>
  <county name="塘厦"/>
  <county name="虎门"/>
  <county name="长安"/>
  <county name="常平"/>
  <county name="万江"/>
  <county name="茶山"/>
  <county name="大朗"/>
  <county name="大岭山"/>
  <county name="道滘"/>
  <county name="东坑"/>
  <county name="凤岗"/>
  <county name="高埗"/>
  <county name="横沥"/>
  <county name="洪梅"/>
  <county name="厚街"/>
  <county name="黄江"/>
  <county name="寮步"/>
  <county name="麻涌"/>
  <county name="企石"/>
  <county name="桥头"/>
  <county name="清溪"/>
  <county name="沙田镇虎门港"/>
  <county name="石碣"/>
  <county name="石龙"/>
  <county name="石排"/>
  <county name="望牛墩"/>
  <county name="谢岗"/>
  <county name="樟木头"/>
  <county name="中堂"/>
  </city>
  <city name="中山市" /> 
 <city name="潮州市">
  <county name="市辖区" /> 
  <county name="湘桥区" /> 
  <county name="潮安县" /> 
  <county name="饶平县" /> 
  </city>
 <city name="揭阳市">
  <county name="市辖区" /> 
  <county name="榕城区" /> 
  <county name="揭东县" /> 
  <county name="揭西县" /> 
  <county name="惠来县" /> 
  <county name="普宁市" /> 
  </city>
 <city name="云浮市">
  <county name="市辖区" /> 
  <county name="云城区" /> 
  <county name="新兴县" /> 
  <county name="郁南县" /> 
  <county name="云安县" /> 
  <county name="罗定市" /> 
  </city>
  </province>
 <province name="广西区">
 <city name="南宁市">
  <county name="市辖区" /> 
  <county name="兴宁区" /> 
  <county name="青秀区" /> 
  <county name="江南区" /> 
  <county name="西乡塘区" /> 
  <county name="良庆区" /> 
  <county name="邕宁区" /> 
  <county name="武鸣县" /> 
  <county name="隆安县" /> 
  <county name="马山县" /> 
  <county name="上林县" /> 
  <county name="宾阳县" /> 
  <county name="横县" /> 
  </city>
 <city name="柳州市">
  <county name="市辖区" /> 
  <county name="城中区" /> 
  <county name="鱼峰区" /> 
  <county name="柳南区" /> 
  <county name="柳北区" /> 
  <county name="柳江县" /> 
  <county name="柳城县" /> 
  <county name="鹿寨县" /> 
  <county name="融安县" /> 
  <county name="融水苗族自治县" /> 
  <county name="三江侗族自治县" /> 
  </city>
 <city name="桂林市">
  <county name="市辖区" /> 
  <county name="秀峰区" /> 
  <county name="叠彩区" /> 
  <county name="象山区" /> 
  <county name="七星区" /> 
  <county name="雁山区" /> 
  <county name="阳朔县" /> 
  <county name="临桂县" /> 
  <county name="灵川县" /> 
  <county name="全州县" /> 
  <county name="兴安县" /> 
  <county name="永福县" /> 
  <county name="灌阳县" /> 
  <county name="龙胜各族自治县" /> 
  <county name="资源县" /> 
  <county name="平乐县" /> 
  <county name="荔蒲县" /> 
  <county name="恭城瑶族自治县" /> 
  </city>
 <city name="梧州市">
  <county name="市辖区" /> 
  <county name="万秀区" /> 
  <county name="蝶山区" /> 
  <county name="长洲区" /> 
  <county name="苍梧县" /> 
  <county name="藤县" /> 
  <county name="蒙山县" /> 
  <county name="岑溪市" /> 
  </city>
 <city name="北海市">
  <county name="市辖区" /> 
  <county name="海城区" /> 
  <county name="银海区" /> 
  <county name="铁山港区" /> 
  <county name="合浦县" /> 
  </city>
 <city name="防城港市">
  <county name="市辖区" /> 
  <county name="港口区" /> 
  <county name="防城区" /> 
  <county name="上思县" /> 
  <county name="东兴市" /> 
  </city>
 <city name="钦州市">
  <county name="市辖区" /> 
  <county name="钦南区" /> 
  <county name="钦北区" /> 
  <county name="灵山县" /> 
  <county name="浦北县" /> 
  </city>
 <city name="贵港市">
  <county name="市辖区" /> 
  <county name="港北区" /> 
  <county name="港南区" /> 
  <county name="覃塘区" /> 
  <county name="平南县" /> 
  <county name="桂平市" /> 
  </city>
 <city name="玉林市">
  <county name="市辖区" /> 
  <county name="玉州区" /> 
  <county name="容县" /> 
  <county name="陆川县" /> 
  <county name="博白县" /> 
  <county name="兴业县" /> 
  <county name="北流市" /> 
  </city>
 <city name="百色市">
  <county name="市辖区" /> 
  <county name="右江区" /> 
  <county name="田阳县" /> 
  <county name="田东县" /> 
  <county name="平果县" /> 
  <county name="德保县" /> 
  <county name="靖西县" /> 
  <county name="那坡县" /> 
  <county name="凌云县" /> 
  <county name="乐业县" /> 
  <county name="田林县" /> 
  <county name="西林县" /> 
  <county name="隆林各族自治县" /> 
  </city>
 <city name="贺州市">
  <county name="市辖区" /> 
  <county name="八步区" /> 
  <county name="昭平县" /> 
  <county name="钟山县" /> 
  <county name="富川瑶族自治县" /> 
  </city>
 <city name="河池市">
  <county name="市辖区" /> 
  <county name="金城江区" /> 
  <county name="南丹县" /> 
  <county name="天峨县" /> 
  <county name="凤山县" /> 
  <county name="东兰县" /> 
  <county name="罗城仫佬族自治县" /> 
  <county name="环江毛南族自治县" /> 
  <county name="巴马瑶族自治县" /> 
  <county name="都安瑶族自治县" /> 
  <county name="大化瑶族自治县" /> 
  <county name="宜州市" /> 
  </city>
 <city name="来宾市">
  <county name="市辖区" /> 
  <county name="兴宾区" /> 
  <county name="忻城县" /> 
  <county name="象州县" /> 
  <county name="武宣县" /> 
  <county name="金秀瑶族自治县" /> 
  <county name="合山市" /> 
  </city>
 <city name="崇左市">
  <county name="市辖区" /> 
  <county name="江洲区" /> 
  <county name="扶绥县" /> 
  <county name="宁明县" /> 
  <county name="龙州县" /> 
  <county name="大新县" /> 
  <county name="天等县" /> 
  <county name="凭祥市" /> 
  </city>
  </province>
 <province name="海南省">
 <city name="海口市">
  <county name="市辖区" /> 
  <county name="秀英区" /> 
  <county name="龙华区" /> 
  <county name="琼山区" /> 
  <county name="美兰区" /> 
  </city>
 <city name="三亚市">
  <county name="市辖区" /> 
  </city>
 <city name="海南直辖县">
  <county name="五指山市" /> 
  <county name="琼海市" /> 
  <county name="儋州市" /> 
  <county name="文昌市" /> 
  <county name="万宁市" /> 
  <county name="东方市" /> 
  <county name="定安县" /> 
  <county name="屯昌县" /> 
  <county name="澄迈县" /> 
  <county name="临高县" /> 
  <county name="白沙黎族自治县" /> 
  <county name="昌江黎族自治县" /> 
  <county name="乐东黎族自治县" /> 
  <county name="陵水黎族自治县" /> 
  <county name="保亭黎族苗族自治县" /> 
  <county name="琼中黎族苗族自治县" /> 
  <county name="西沙群岛" /> 
  <county name="南沙群岛" /> 
  <county name="中沙群岛的岛礁及其海域" /> 
  </city>
  </province>
 <province name="重庆市">
 <city name="重庆市">
  <county name="万州区" /> 
  <county name="涪陵区" /> 
  <county name="渝中区" /> 
  <county name="大渡口区" /> 
  <county name="江北区" /> 
  <county name="沙坪坝区" /> 
  <county name="九龙坡区" /> 
  <county name="南岸区" /> 
  <county name="北碚区" /> 
  <county name="万盛区" /> 
  <county name="双桥区" /> 
  <county name="渝北区" /> 
  <county name="巴南区" /> 
  <county name="黔江区" /> 
  <county name="长寿区" />
  <county name="璧山区" />
  <county name="江津区" /> 
  <county name="合川区" /> 
  <county name="永川区" /> 
  <county name="南川区" /> 
  </city>
 <city name="县">
  <county name="綦江县" /> 
  <county name="潼南县" /> 
  <county name="铜梁县" /> 
  <county name="大足县" /> 
  <county name="荣昌县" />
  <county name="梁平县" /> 
  <county name="城口县" /> 
  <county name="丰都县" /> 
  <county name="垫江县" /> 
  <county name="武隆县" /> 
  <county name="忠县" /> 
  <county name="开县" /> 
  <county name="云阳县" /> 
  <county name="奉节县" /> 
  <county name="巫山县" /> 
  <county name="巫溪县" /> 
  <county name="石柱土家族自治县" /> 
  <county name="秀山土家族苗族自治县" /> 
  <county name="酉阳土家族苗族自治县" /> 
  <county name="彭水苗族土家族自治县" /> 
  </city>
 
  </province>
 <province name="四川省">
 <city name="成都市">
  <county name="市辖区" /> 
  <county name="锦江区" /> 
  <county name="青羊区" /> 
  <county name="金牛区" /> 
  <county name="武侯区" /> 
  <county name="成华区" /> 
  <county name="龙泉驿区" /> 
  <county name="青白江区" /> 
  <county name="新都区" /> 
  <county name="温江区" /> 
  <county name="金堂县" /> 
  <county name="双流县" /> 
  <county name="郫县" /> 
  <county name="大邑县" /> 
  <county name="蒲江县" /> 
  <county name="新津县" /> 
  <county name="都江堰市" /> 
  <county name="彭州市" /> 
  <county name="邛崃市" /> 
  <county name="崇州市" /> 
  </city>
 <city name="自贡市">
  <county name="市辖区" /> 
  <county name="自流井区" /> 
  <county name="贡井区" /> 
  <county name="大安区" /> 
  <county name="沿滩区" /> 
  <county name="荣县" /> 
  <county name="富顺县" /> 
  </city>
 <city name="攀枝花市">
  <county name="市辖区" /> 
  <county name="东区" /> 
  <county name="西区" /> 
  <county name="仁和区" /> 
  <county name="米易县" /> 
  <county name="盐边县" /> 
  </city>
 <city name="泸州市">
  <county name="市辖区" /> 
  <county name="江阳区" /> 
  <county name="纳溪区" /> 
  <county name="龙马潭区" /> 
  <county name="泸县" /> 
  <county name="合江县" /> 
  <county name="叙永县" /> 
  <county name="古蔺县" /> 
  </city>
 <city name="德阳市">
  <county name="市辖区" /> 
  <county name="旌阳区" /> 
  <county name="中江县" /> 
  <county name="罗江县" /> 
  <county name="广汉市" /> 
  <county name="什邡市" /> 
  <county name="绵竹市" /> 
  </city>
 <city name="绵阳市">
  <county name="市辖区" /> 
  <county name="涪城区" /> 
  <county name="游仙区" /> 
  <county name="三台县" /> 
  <county name="盐亭县" /> 
  <county name="安县" /> 
  <county name="梓潼县" /> 
  <county name="北川羌族自治县" /> 
  <county name="平武县" /> 
  <county name="江油市" /> 
  </city>
 <city name="广元市">
  <county name="市辖区" /> 
  <county name="市中区" /> 
  <county name="元坝区" /> 
  <county name="朝天区" /> 
  <county name="旺苍县" /> 
  <county name="青川县" /> 
  <county name="剑阁县" /> 
  <county name="苍溪县" /> 
  </city>
 <city name="遂宁市">
  <county name="市辖区" /> 
  <county name="船山区" /> 
  <county name="安居区" /> 
  <county name="蓬溪县" /> 
  <county name="射洪县" /> 
  <county name="大英县" /> 
  </city>
 <city name="内江市">
  <county name="市辖区" /> 
  <county name="市中区" /> 
  <county name="东兴区" /> 
  <county name="威远县" /> 
  <county name="资中县" /> 
  <county name="隆昌县" /> 
  </city>
 <city name="乐山市">
  <county name="市辖区" /> 
  <county name="市中区" /> 
  <county name="沙湾区" /> 
  <county name="五通桥区" /> 
  <county name="金口河区" /> 
  <county name="犍为县" /> 
  <county name="井研县" /> 
  <county name="夹江县" /> 
  <county name="沐川县" /> 
  <county name="峨边彝族自治县" /> 
  <county name="马边彝族自治县" /> 
  <county name="峨眉山市" /> 
  </city>
 <city name="南充市">
  <county name="市辖区" /> 
  <county name="顺庆区" /> 
  <county name="高坪区" /> 
  <county name="嘉陵区" /> 
  <county name="南部县" /> 
  <county name="营山县" /> 
  <county name="蓬安县" /> 
  <county name="仪陇县" /> 
  <county name="西充县" /> 
  <county name="阆中市" /> 
  </city>
 <city name="眉山市">
  <county name="市辖区" /> 
  <county name="东坡区" /> 
  <county name="仁寿县" /> 
  <county name="彭山县" /> 
  <county name="洪雅县" /> 
  <county name="丹棱县" /> 
  <county name="青神县" /> 
  </city>
 <city name="宜宾市">
  <county name="市辖区" /> 
  <county name="翠屏区" /> 
  <county name="宜宾县" /> 
  <county name="南溪县" /> 
  <county name="江安县" /> 
  <county name="长宁县" /> 
  <county name="高县" /> 
  <county name="珙县" /> 
  <county name="筠连县" /> 
  <county name="兴文县" /> 
  <county name="屏山县" /> 
  </city>
 <city name="广安市">
  <county name="市辖区" /> 
  <county name="广安区" /> 
  <county name="岳池县" /> 
  <county name="武胜县" /> 
  <county name="邻水县" /> 
  <county name="华莹市" /> 
  </city>
 <city name="达州市">
  <county name="市辖区" /> 
  <county name="通川区" /> 
  <county name="达县" /> 
  <county name="宣汉县" /> 
  <county name="开江县" /> 
  <county name="大竹县" /> 
  <county name="渠县" /> 
  <county name="万源市" /> 
  </city>
 <city name="雅安市">
  <county name="市辖区" /> 
  <county name="雨城区" /> 
  <county name="名山县" /> 
  <county name="荥经县" /> 
  <county name="汉源县" /> 
  <county name="石棉县" /> 
  <county name="天全县" /> 
  <county name="芦山县" /> 
  <county name="宝兴县" /> 
  </city>
 <city name="巴中市">
  <county name="市辖区" /> 
  <county name="巴州区" /> 
  <county name="通江县" /> 
  <county name="南江县" /> 
  <county name="平昌县" /> 
  </city>
 <city name="资阳市">
  <county name="市辖区" /> 
  <county name="雁江区" /> 
  <county name="安岳县" /> 
  <county name="乐至县" /> 
  <county name="简阳市" /> 
  </city>
 <city name="阿坝自治州">
  <county name="汶川县" /> 
  <county name="理县" /> 
  <county name="茂县" /> 
  <county name="松潘县" /> 
  <county name="九寨沟县" /> 
  <county name="金川县" /> 
  <county name="小金县" /> 
  <county name="黑水县" /> 
  <county name="马尔康县" /> 
  <county name="壤塘县" /> 
  <county name="阿坝县" /> 
  <county name="若尔盖县" /> 
  <county name="红原县" /> 
  </city>
 <city name="甘孜自治州">
  <county name="康定县" /> 
  <county name="泸定县" /> 
  <county name="丹巴县" /> 
  <county name="九龙县" /> 
  <county name="雅江县" /> 
  <county name="道孚县" /> 
  <county name="炉霍县" /> 
  <county name="甘孜县" /> 
  <county name="新龙县" /> 
  <county name="德格县" /> 
  <county name="白玉县" /> 
  <county name="石渠县" /> 
  <county name="色达县" /> 
  <county name="理塘县" /> 
  <county name="巴塘县" /> 
  <county name="乡城县" /> 
  <county name="稻城县" /> 
  <county name="得荣县" /> 
  </city>
 <city name="凉山自治州">
  <county name="西昌市" /> 
  <county name="木里藏族自治县" /> 
  <county name="盐源县" /> 
  <county name="德昌县" /> 
  <county name="会理县" /> 
  <county name="会东县" /> 
  <county name="宁南县" /> 
  <county name="普格县" /> 
  <county name="布拖县" /> 
  <county name="金阳县" /> 
  <county name="昭觉县" /> 
  <county name="喜德县" /> 
  <county name="冕宁县" /> 
  <county name="越西县" /> 
  <county name="甘洛县" /> 
  <county name="美姑县" /> 
  <county name="雷波县" /> 
  </city>
  </province>
 <province name="贵州省">
 <city name="贵阳市">
  <county name="市辖区" /> 
  <county name="南明区" /> 
  <county name="云岩区" /> 
  <county name="花溪区" /> 
  <county name="乌当区" /> 
  <county name="白云区" /> 
  <county name="小河区" /> 
  <county name="开阳县" /> 
  <county name="息烽县" /> 
  <county name="修文县" /> 
  <county name="清镇市" /> 
  </city>
 <city name="六盘水市">
  <county name="钟山区" /> 
  <county name="六枝特区" /> 
  <county name="水城县" /> 
  <county name="盘县" /> 
  </city>
 <city name="遵义市">
  <county name="市辖区" /> 
  <county name="红花岗区" /> 
  <county name="汇川区" /> 
  <county name="遵义县" /> 
  <county name="桐梓县" /> 
  <county name="绥阳县" /> 
  <county name="正安县" /> 
  <county name="道真仡佬族苗族自治县" /> 
  <county name="务川仡佬族苗族自治县" /> 
  <county name="凤冈县" /> 
  <county name="湄潭县" /> 
  <county name="余庆县" /> 
  <county name="习水县" /> 
  <county name="赤水市" /> 
  <county name="仁怀市" /> 
  </city>
 <city name="安顺市">
  <county name="市辖区" /> 
  <county name="西秀区" /> 
  <county name="平坝县" /> 
  <county name="普定县" /> 
  <county name="镇宁布依族苗族自治县" /> 
  <county name="关岭布依族苗族自治县" /> 
  <county name="紫云苗族布依族自治县" /> 
  </city>
 <city name="铜仁地区">
  <county name="铜仁市" /> 
  <county name="江口县" /> 
  <county name="玉屏侗族自治县" /> 
  <county name="石阡县" /> 
  <county name="思南县" /> 
  <county name="印江土家族苗族自治县" /> 
  <county name="德江县" /> 
  <county name="沿河土家族自治县" /> 
  <county name="松桃苗族自治县" /> 
  <county name="万山特区" /> 
  </city>
 <city name="黔西南自治州">
  <county name="兴义市" /> 
  <county name="兴仁县" /> 
  <county name="普安县" /> 
  <county name="晴隆县" /> 
  <county name="贞丰县" /> 
  <county name="望谟县" /> 
  <county name="册亨县" /> 
  <county name="安龙县" /> 
  </city>
 <city name="毕节地区">
  <county name="毕节市" /> 
  <county name="大方县" /> 
  <county name="黔西县" /> 
  <county name="金沙县" /> 
  <county name="织金县" /> 
  <county name="纳雍县" /> 
  <county name="威宁彝族回族苗族自治县" /> 
  <county name="赫章县" /> 
  </city>
 <city name="黔东南自治州">
  <county name="凯里市" /> 
  <county name="黄平县" /> 
  <county name="施秉县" /> 
  <county name="三穗县" /> 
  <county name="镇远县" /> 
  <county name="岑巩县" /> 
  <county name="天柱县" /> 
  <county name="锦屏县" /> 
  <county name="剑河县" /> 
  <county name="台江县" /> 
  <county name="黎平县" /> 
  <county name="榕江县" /> 
  <county name="从江县" /> 
  <county name="雷山县" /> 
  <county name="麻江县" /> 
  <county name="丹寨县" /> 
  </city>
 <city name="黔南自治州">
  <county name="都匀市" /> 
  <county name="福泉市" /> 
  <county name="荔波县" /> 
  <county name="贵定县" /> 
  <county name="瓮安县" /> 
  <county name="独山县" /> 
  <county name="平塘县" /> 
  <county name="罗甸县" /> 
  <county name="长顺县" /> 
  <county name="龙里县" /> 
  <county name="惠水县" /> 
  <county name="三都水族自治县" /> 
  </city>
  </province>
 <province name="云南省">
 <city name="昆明市">
  <county name="市辖区" /> 
  <county name="五华区" /> 
  <county name="盘龙区" /> 
  <county name="官渡区" /> 
  <county name="西山区" /> 
  <county name="东川区" /> 
  <county name="呈贡县" /> 
  <county name="晋宁县" /> 
  <county name="富民县" /> 
  <county name="宜良县" /> 
  <county name="石林彝族自治县" /> 
  <county name="嵩明县" /> 
  <county name="禄劝彝族苗族自治县" /> 
  <county name="寻甸回族彝族自治县" /> 
  <county name="安宁市" /> 
  </city>
 <city name="曲靖市">
  <county name="市辖区" /> 
  <county name="麒麟区" /> 
  <county name="马龙县" /> 
  <county name="陆良县" /> 
  <county name="师宗县" /> 
  <county name="罗平县" /> 
  <county name="富源县" /> 
  <county name="会泽县" /> 
  <county name="沾益县" /> 
  <county name="宣威市" /> 
  </city>
 <city name="玉溪市">
  <county name="市辖区" /> 
  <county name="红塔区" /> 
  <county name="江川县" /> 
  <county name="澄江县" /> 
  <county name="通海县" /> 
  <county name="华宁县" /> 
  <county name="易门县" /> 
  <county name="峨山彝族自治县" /> 
  <county name="新平彝族傣族自治县" /> 
  <county name="元江哈尼族彝族傣族自治县" /> 
  </city>
 <city name="保山市">
  <county name="市辖区" /> 
  <county name="隆阳区" /> 
  <county name="施甸县" /> 
  <county name="腾冲县" /> 
  <county name="龙陵县" /> 
  <county name="昌宁县" /> 
  </city>
 <city name="昭通市">
  <county name="市辖区" /> 
  <county name="昭阳区" /> 
  <county name="鲁甸县" /> 
  <county name="巧家县" /> 
  <county name="盐津县" /> 
  <county name="大关县" /> 
  <county name="永善县" /> 
  <county name="绥江县" /> 
  <county name="镇雄县" /> 
  <county name="彝良县" /> 
  <county name="威信县" /> 
  <county name="水富县" /> 
  </city>
 <city name="丽江市">
  <county name="市辖区" /> 
  <county name="古城区" /> 
  <county name="玉龙纳西族自治县" /> 
  <county name="永胜县" /> 
  <county name="华坪县" /> 
  <county name="宁蒗彝族自治县" /> 
  </city>
 <city name="思茅市">
  <county name="市辖区" /> 
  <county name="翠云区" /> 
  <county name="普洱哈尼族彝族自治县" /> 
  <county name="墨江哈尼族自治县" /> 
  <county name="景东彝族自治县" /> 
  <county name="景谷傣族彝族自治县" /> 
  <county name="镇沅彝族哈尼族拉祜族自治县" /> 
  <county name="江城哈尼族彝族自治县" /> 
  <county name="孟连傣族拉祜族佤族自治县" /> 
  <county name="澜沧拉祜族自治县" /> 
  <county name="西盟佤族自治县" /> 
  </city>
 <city name="临沧市">
  <county name="市辖区" /> 
  <county name="临翔区" /> 
  <county name="凤庆县" /> 
  <county name="云县" /> 
  <county name="永德县" /> 
  <county name="镇康县" /> 
  <county name="双江拉祜族佤族布朗族傣族自治县" /> 
  <county name="耿马傣族佤族自治县" /> 
  <county name="沧源佤族自治县" /> 
  </city>
 <city name="楚雄自治州">
  <county name="楚雄市" /> 
  <county name="双柏县" /> 
  <county name="牟定县" /> 
  <county name="南华县" /> 
  <county name="姚安县" /> 
  <county name="大姚县" /> 
  <county name="永仁县" /> 
  <county name="元谋县" /> 
  <county name="武定县" /> 
  <county name="禄丰县" /> 
  </city>
 <city name="红河自治州">
  <county name="个旧市" /> 
  <county name="开远市" /> 
  <county name="蒙自县" /> 
  <county name="屏边苗族自治县" /> 
  <county name="建水县" /> 
  <county name="石屏县" /> 
  <county name="弥勒县" /> 
  <county name="泸西县" /> 
  <county name="元阳县" /> 
  <county name="红河县" /> 
  <county name="金平苗族瑶族傣族自治县" /> 
  <county name="绿春县" /> 
  <county name="河口瑶族自治县" /> 
  </city>
 <city name="文山自治州">
  <county name="文山县" /> 
  <county name="砚山县" /> 
  <county name="西畴县" /> 
  <county name="麻栗坡县" /> 
  <county name="马关县" /> 
  <county name="丘北县" /> 
  <county name="广南县" /> 
  <county name="富宁县" /> 
  </city>
 <city name="西双版纳州">
  <county name="景洪市" /> 
  <county name="勐海县" /> 
  <county name="勐腊县" /> 
  </city>
 <city name="大理自治州">
  <county name="大理市" /> 
  <county name="漾濞彝族自治县" /> 
  <county name="祥云县" /> 
  <county name="宾川县" /> 
  <county name="弥渡县" /> 
  <county name="南涧彝族自治县" /> 
  <county name="巍山彝族回族自治县" /> 
  <county name="永平县" /> 
  <county name="云龙县" /> 
  <county name="洱源县" /> 
  <county name="剑川县" /> 
  <county name="鹤庆县" /> 
  </city>
 <city name="德宏自治州">
  <county name="瑞丽市" /> 
  <county name="潞西市" /> 
  <county name="梁河县" /> 
  <county name="盈江县" /> 
  <county name="陇川县" /> 
  </city>
 <city name="怒江傈自治州">
  <county name="泸水县" /> 
  <county name="福贡县" /> 
  <county name="贡山独龙族怒族自治县" /> 
  <county name="兰坪白族普米族自治县" /> 
  </city>
 <city name="迪庆自治州">
  <county name="香格里拉县" /> 
  <county name="德钦县" /> 
  <county name="维西傈僳族自治县" /> 
  </city>
  </province>
 <province name="西藏区">
 <city name="拉萨市">
  <county name="市辖区" /> 
  <county name="城关区" /> 
  <county name="林周县" /> 
  <county name="当雄县" /> 
  <county name="尼木县" /> 
  <county name="曲水县" /> 
  <county name="堆龙德庆县" /> 
  <county name="达孜县" /> 
  <county name="墨竹工卡县" /> 
  </city>
 <city name="昌都地区">
  <county name="昌都县" /> 
  <county name="江达县" /> 
  <county name="贡觉县" /> 
  <county name="类乌齐县" /> 
  <county name="丁青县" /> 
  <county name="察雅县" /> 
  <county name="八宿县" /> 
  <county name="左贡县" /> 
  <county name="芒康县" /> 
  <county name="洛隆县" /> 
  <county name="边坝县" /> 
  </city>
 <city name="山南地区">
  <county name="乃东县" /> 
  <county name="扎囊县" /> 
  <county name="贡嘎县" /> 
  <county name="桑日县" /> 
  <county name="琼结县" /> 
  <county name="曲松县" /> 
  <county name="措美县" /> 
  <county name="洛扎县" /> 
  <county name="加查县" /> 
  <county name="隆子县" /> 
  <county name="错那县" /> 
  <county name="浪卡子县" /> 
  </city>
 <city name="日喀则地区">
  <county name="日喀则市" /> 
  <county name="南木林县" /> 
  <county name="江孜县" /> 
  <county name="定日县" /> 
  <county name="萨迦县" /> 
  <county name="拉孜县" /> 
  <county name="昂仁县" /> 
  <county name="谢通门县" /> 
  <county name="白朗县" /> 
  <county name="仁布县" /> 
  <county name="康马县" /> 
  <county name="定结县" /> 
  <county name="仲巴县" /> 
  <county name="亚东县" /> 
  <county name="吉隆县" /> 
  <county name="聂拉木县" /> 
  <county name="萨嘎县" /> 
  <county name="岗巴县" /> 
  </city>
 <city name="那曲地区">
  <county name="那曲县" /> 
  <county name="嘉黎县" /> 
  <county name="比如县" /> 
  <county name="聂荣县" /> 
  <county name="安多县" /> 
  <county name="申扎县" /> 
  <county name="索县" /> 
  <county name="班戈县" /> 
  <county name="巴青县" /> 
  <county name="尼玛县" /> 
  </city>
 <city name="阿里地区">
  <county name="普兰县" /> 
  <county name="札达县" /> 
  <county name="噶尔县" /> 
  <county name="日土县" /> 
  <county name="革吉县" /> 
  <county name="改则县" /> 
  <county name="措勤县" /> 
  </city>
 <city name="林芝地区">
  <county name="林芝县" /> 
  <county name="工布江达县" /> 
  <county name="米林县" /> 
  <county name="墨脱县" /> 
  <county name="波密县" /> 
  <county name="察隅县" /> 
  <county name="朗县" /> 
  </city>
  </province>
 <province name="陕西省">
 <city name="西安市">
  <county name="市辖区" /> 
  <county name="新城区" /> 
  <county name="碑林区" /> 
  <county name="莲湖区" /> 
  <county name="灞桥区" /> 
  <county name="未央区" /> 
  <county name="雁塔区" /> 
  <county name="阎良区" /> 
  <county name="临潼区" /> 
  <county name="长安区" /> 
  <county name="蓝田县" /> 
  <county name="周至县" /> 
  <county name="户县" /> 
  <county name="高陵县" /> 
  </city>
 <city name="铜川市">
  <county name="市辖区" /> 
  <county name="王益区" /> 
  <county name="印台区" /> 
  <county name="耀州区" /> 
  <county name="宜君县" /> 
  </city>
 <city name="宝鸡市">
  <county name="市辖区" /> 
  <county name="渭滨区" /> 
  <county name="金台区" /> 
  <county name="陈仓区" /> 
  <county name="凤翔县" /> 
  <county name="岐山县" /> 
  <county name="扶风县" /> 
  <county name="眉县" /> 
  <county name="陇县" /> 
  <county name="千阳县" /> 
  <county name="麟游县" /> 
  <county name="凤县" /> 
  <county name="太白县" /> 
  </city>
 <city name="咸阳市">
  <county name="市辖区" /> 
  <county name="秦都区" /> 
  <county name="杨凌区" /> 
  <county name="渭城区" /> 
  <county name="三原县" /> 
  <county name="泾阳县" /> 
  <county name="乾县" /> 
  <county name="礼泉县" /> 
  <county name="永寿县" /> 
  <county name="彬县" /> 
  <county name="长武县" /> 
  <county name="旬邑县" /> 
  <county name="淳化县" /> 
  <county name="武功县" /> 
  <county name="兴平市" /> 
  </city>
 <city name="渭南市">
  <county name="市辖区" /> 
  <county name="临渭区" /> 
  <county name="华县" /> 
  <county name="潼关县" /> 
  <county name="大荔县" /> 
  <county name="合阳县" /> 
  <county name="澄城县" /> 
  <county name="蒲城县" /> 
  <county name="白水县" /> 
  <county name="富平县" /> 
  <county name="韩城市" /> 
  <county name="华阴市" /> 
  </city>
 <city name="延安市">
  <county name="市辖区" /> 
  <county name="宝塔区" /> 
  <county name="延长县" /> 
  <county name="延川县" /> 
  <county name="子长县" /> 
  <county name="安塞县" /> 
  <county name="志丹县" /> 
  <county name="吴旗县" /> 
  <county name="甘泉县" /> 
  <county name="富县" /> 
  <county name="洛川县" /> 
  <county name="宜川县" /> 
  <county name="黄龙县" /> 
  <county name="黄陵县" /> 
  </city>
 <city name="汉中市">
  <county name="市辖区" /> 
  <county name="汉台区" /> 
  <county name="南郑县" /> 
  <county name="城固县" /> 
  <county name="洋县" /> 
  <county name="西乡县" /> 
  <county name="勉县" /> 
  <county name="宁强县" /> 
  <county name="略阳县" /> 
  <county name="镇巴县" /> 
  <county name="留坝县" /> 
  <county name="佛坪县" /> 
  </city>
 <city name="榆林市">
  <county name="市辖区" /> 
  <county name="榆阳区" /> 
  <county name="神木县" /> 
  <county name="府谷县" /> 
  <county name="横山县" /> 
  <county name="靖边县" /> 
  <county name="定边县" /> 
  <county name="绥德县" /> 
  <county name="米脂县" /> 
  <county name="佳县" /> 
  <county name="吴堡县" /> 
  <county name="清涧县" /> 
  <county name="子洲县" /> 
  </city>
 <city name="安康市">
  <county name="市辖区" /> 
  <county name="汉滨区" /> 
  <county name="汉阴县" /> 
  <county name="石泉县" /> 
  <county name="宁陕县" /> 
  <county name="紫阳县" /> 
  <county name="岚皋县" /> 
  <county name="平利县" /> 
  <county name="镇坪县" /> 
  <county name="旬阳县" /> 
  <county name="白河县" /> 
  </city>
 <city name="商洛市">
  <county name="市辖区" /> 
  <county name="商州区" /> 
  <county name="洛南县" /> 
  <county name="丹凤县" /> 
  <county name="商南县" /> 
  <county name="山阳县" /> 
  <county name="镇安县" /> 
  <county name="柞水县" /> 
  </city>
  </province>
 <province name="甘肃省">
 <city name="兰州市">
  <county name="市辖区" /> 
  <county name="城关区" /> 
  <county name="七里河区" /> 
  <county name="西固区" /> 
  <county name="安宁区" /> 
  <county name="红古区" /> 
  <county name="永登县" /> 
  <county name="皋兰县" /> 
  <county name="榆中县" /> 
  </city>
 <city name="嘉峪关市">
  <county name="市辖区" /> 
  </city>
 <city name="金昌市">
  <county name="市辖区" /> 
  <county name="金川区" /> 
  <county name="永昌县" /> 
  </city>
 <city name="白银市">
  <county name="市辖区" /> 
  <county name="白银区" /> 
  <county name="平川区" /> 
  <county name="靖远县" /> 
  <county name="会宁县" /> 
  <county name="景泰县" /> 
  </city>
 <city name="天水市">
  <county name="市辖区" /> 
  <county name="秦城区" /> 
  <county name="北道区" /> 
  <county name="清水县" /> 
  <county name="秦安县" /> 
  <county name="甘谷县" /> 
  <county name="武山县" /> 
  <county name="张家川回族自治县" /> 
  </city>
 <city name="武威市">
  <county name="市辖区" /> 
  <county name="凉州区" /> 
  <county name="民勤县" /> 
  <county name="古浪县" /> 
  <county name="天祝藏族自治县" /> 
  </city>
 <city name="张掖市">
  <county name="市辖区" /> 
  <county name="甘州区" /> 
  <county name="肃南裕固族自治县" /> 
  <county name="民乐县" /> 
  <county name="临泽县" /> 
  <county name="高台县" /> 
  <county name="山丹县" /> 
  </city>
 <city name="平凉市">
  <county name="市辖区" /> 
  <county name="崆峒区" /> 
  <county name="泾川县" /> 
  <county name="灵台县" /> 
  <county name="崇信县" /> 
  <county name="华亭县" /> 
  <county name="庄浪县" /> 
  <county name="静宁县" /> 
  </city>
 <city name="酒泉市">
  <county name="市辖区" /> 
  <county name="肃州区" /> 
  <county name="金塔县" /> 
  <county name="安西县" /> 
  <county name="肃北蒙古族自治县" /> 
  <county name="阿克塞哈萨克族自治县" /> 
  <county name="玉门市" /> 
  <county name="敦煌市" /> 
  </city>
 <city name="庆阳市">
  <county name="市辖区" /> 
  <county name="西峰区" /> 
  <county name="庆城县" /> 
  <county name="环县" /> 
  <county name="华池县" /> 
  <county name="合水县" /> 
  <county name="正宁县" /> 
  <county name="宁县" /> 
  <county name="镇原县" /> 
  </city>
 <city name="定西市">
  <county name="市辖区" /> 
  <county name="安定区" /> 
  <county name="通渭县" /> 
  <county name="陇西县" /> 
  <county name="渭源县" /> 
  <county name="临洮县" /> 
  <county name="漳县" /> 
  <county name="岷县" /> 
  </city>
 <city name="陇南市">
  <county name="市辖区" /> 
  <county name="武都区" /> 
  <county name="成县" /> 
  <county name="文县" /> 
  <county name="宕昌县" /> 
  <county name="康县" /> 
  <county name="西和县" /> 
  <county name="礼县" /> 
  <county name="徽县" /> 
  <county name="两当县" /> 
  </city>
 <city name="临夏自治州">
  <county name="临夏市" /> 
  <county name="临夏县" /> 
  <county name="康乐县" /> 
  <county name="永靖县" /> 
  <county name="广河县" /> 
  <county name="和政县" /> 
  <county name="东乡族自治县" /> 
  <county name="积石山保安族东乡族撒拉族自治县" /> 
  </city>
 <city name="甘南自治州">
  <county name="合作市" /> 
  <county name="临潭县" /> 
  <county name="卓尼县" /> 
  <county name="舟曲县" /> 
  <county name="迭部县" /> 
  <county name="玛曲县" /> 
  <county name="碌曲县" /> 
  <county name="夏河县" /> 
  </city>
  </province>
 <province name="青海省">
 <city name="西宁市">
  <county name="市辖区" /> 
  <county name="城东区" /> 
  <county name="城中区" /> 
  <county name="城西区" /> 
  <county name="城北区" /> 
  <county name="大通回族土族自治县" /> 
  <county name="湟中县" /> 
  <county name="湟源县" /> 
  </city>
 <city name="海东地区">
  <county name="平安县" /> 
  <county name="民和回族土族自治县" /> 
  <county name="乐都县" /> 
  <county name="互助土族自治县" /> 
  <county name="化隆回族自治县" /> 
  <county name="循化撒拉族自治县" /> 
  </city>
 <city name="海北自治州">
  <county name="门源回族自治县" /> 
  <county name="祁连县" /> 
  <county name="海晏县" /> 
  <county name="刚察县" /> 
  </city>
 <city name="黄南自治州">
  <county name="同仁县" /> 
  <county name="尖扎县" /> 
  <county name="泽库县" /> 
  <county name="河南蒙古族自治县" /> 
  </city>
 <city name="海南自治州">
  <county name="共和县" /> 
  <county name="同德县" /> 
  <county name="贵德县" /> 
  <county name="兴海县" /> 
  <county name="贵南县" /> 
  </city>
 <city name="果洛自治州">
  <county name="玛沁县" /> 
  <county name="班玛县" /> 
  <county name="甘德县" /> 
  <county name="达日县" /> 
  <county name="久治县" /> 
  <county name="玛多县" /> 
  </city>
 <city name="玉树自治州">
  <county name="玉树县" /> 
  <county name="杂多县" /> 
  <county name="称多县" /> 
  <county name="治多县" /> 
  <county name="囊谦县" /> 
  <county name="曲麻莱县" /> 
  </city>
 <city name="海西自治州">
  <county name="格尔木市" /> 
  <county name="德令哈市" /> 
  <county name="乌兰县" /> 
  <county name="都兰县" /> 
  <county name="天峻县" /> 
  </city>
  </province>
 <province name="宁夏区">
 <city name="银川市">
  <county name="市辖区" /> 
  <county name="兴庆区" /> 
  <county name="西夏区" /> 
  <county name="金凤区" /> 
  <county name="永宁县" /> 
  <county name="贺兰县" /> 
  <county name="灵武市" /> 
  </city>
 <city name="石嘴山市">
  <county name="市辖区" /> 
  <county name="大武口区" /> 
  <county name="惠农区" /> 
  <county name="平罗县" /> 
  </city>
 <city name="吴忠市">
  <county name="市辖区" /> 
  <county name="利通区" /> 
  <county name="盐池县" /> 
  <county name="同心县" /> 
  <county name="青铜峡市" /> 
  </city>
 <city name="固原市">
  <county name="市辖区" /> 
  <county name="原州区" /> 
  <county name="西吉县" /> 
  <county name="隆德县" /> 
  <county name="泾源县" /> 
  <county name="彭阳县" /> 
  </city>
 <city name="中卫市">
  <county name="市辖区" /> 
  <county name="沙坡头区" /> 
  <county name="中宁县" /> 
  <county name="海原县" /> 
  </city>
  </province>
 <province name="新疆维吾尔自治区">
 <city name="乌鲁木齐市">
  <county name="市辖区" /> 
  <county name="天山区" /> 
  <county name="沙依巴克区" /> 
  <county name="新市区" /> 
  <county name="水磨沟区" /> 
  <county name="头屯河区" /> 
  <county name="达坂城区" /> 
  <county name="东山区" /> 
  <county name="乌鲁木齐县" /> 
  </city>
 <city name="克拉玛依市">
  <county name="市辖区" /> 
  <county name="独山子区" /> 
  <county name="克拉玛依区" /> 
  <county name="白碱滩区" /> 
  <county name="乌尔禾区" /> 
  </city>
 <city name="吐鲁番地区">
  <county name="吐鲁番市" /> 
  <county name="鄯善县" /> 
  <county name="托克逊县" /> 
  </city>
 <city name="哈密地区">
  <county name="哈密市" /> 
  <county name="巴里坤哈萨克自治县" /> 
  <county name="伊吾县" /> 
  </city>
 <city name="昌吉自治州">
  <county name="昌吉市" /> 
  <county name="阜康市" /> 
  <county name="米泉市" /> 
  <county name="呼图壁县" /> 
  <county name="玛纳斯县" /> 
  <county name="奇台县" /> 
  <county name="吉木萨尔县" /> 
  <county name="木垒哈萨克自治县" /> 
  </city>
 <city name="博尔塔拉州">
  <county name="博乐市" /> 
  <county name="精河县" /> 
  <county name="温泉县" /> 
  </city>
 <city name="巴音郭楞州">
  <county name="库尔勒市" /> 
  <county name="轮台县" /> 
  <county name="尉犁县" /> 
  <county name="若羌县" /> 
  <county name="且末县" /> 
  <county name="焉耆回族自治县" /> 
  <county name="和静县" /> 
  <county name="和硕县" /> 
  <county name="博湖县" /> 
  </city>
 <city name="阿克苏地区">
  <county name="阿克苏市" /> 
  <county name="温宿县" /> 
  <county name="库车县" /> 
  <county name="沙雅县" /> 
  <county name="新和县" /> 
  <county name="拜城县" /> 
  <county name="乌什县" /> 
  <county name="阿瓦提县" /> 
  <county name="柯坪县" /> 
  </city>
 <city name="克孜勒苏州">
  <county name="阿图什市" /> 
  <county name="阿克陶县" /> 
  <county name="阿合奇县" /> 
  <county name="乌恰县" /> 
  </city>
 <city name="喀什地区">
  <county name="喀什市" /> 
  <county name="疏附县" /> 
  <county name="疏勒县" /> 
  <county name="英吉沙县" /> 
  <county name="泽普县" /> 
  <county name="莎车县" /> 
  <county name="叶城县" /> 
  <county name="麦盖提县" /> 
  <county name="岳普湖县" /> 
  <county name="伽师县" /> 
  <county name="巴楚县" /> 
  <county name="塔什库尔干塔吉克自治县" /> 
  </city>
 <city name="和田地区">
  <county name="和田市" /> 
  <county name="和田县" /> 
  <county name="墨玉县" /> 
  <county name="皮山县" /> 
  <county name="洛浦县" /> 
  <county name="策勒县" /> 
  <county name="于田县" /> 
  <county name="民丰县" /> 
  </city>
 <city name="伊犁自治州">
  <county name="伊宁市" /> 
  <county name="奎屯市" /> 
  <county name="伊宁县" /> 
  <county name="察布查尔锡伯自治县" /> 
  <county name="霍城县" /> 
  <county name="巩留县" /> 
  <county name="新源县" /> 
  <county name="昭苏县" /> 
  <county name="特克斯县" /> 
  <county name="尼勒克县" /> 
  </city>
 <city name="塔城地区">
  <county name="塔城市" /> 
  <county name="乌苏市" /> 
  <county name="额敏县" /> 
  <county name="沙湾县" /> 
  <county name="托里县" /> 
  <county name="裕民县" /> 
  <county name="和布克赛尔蒙古自治县" /> 
  </city>
 <city name="阿勒泰地区">
  <county name="阿勒泰市" /> 
  <county name="布尔津县" /> 
  <county name="富蕴县" /> 
  <county name="福海县" /> 
  <county name="哈巴河县" /> 
  <county name="青河县" /> 
  <county name="吉木乃县" /> 
  </city>
 <city name="新疆省辖单位">
  <county name="石河子市" /> 
  <county name="阿拉尔市" /> 
  <county name="图木舒克市" /> 
  <county name="五家渠市" /> 
  </city>
  </province>
  <province name="台湾省" /> 
  <province name="香港特区" /> 
  <province name="澳门特区" /> 
  </address>
data;
	$xmlstring = @simplexml_load_string($str, NULL, LIBXML_NOCDATA); 

	$val = json_decode(json_encode($xmlstring), true);

	return $val;
    }    
}
