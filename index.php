<?php
include './Base.php';
header("Content-type:text/html;charset=utf-8");
ini_set('date.timezone','Asia/Shanghai');
class Alipay extends Base
{
    const TRANSFER = 'https://openapi.alipay.com/gateway.do';

    public function __construct() {
     
    
    }
    //查询转账
    public function searchPay(){
        //公共请求参数
         $pub_params = [
            'app_id'    => self::APPID,
            'method'    =>  'alipay.fund.trans.order.query', //接口名称 应填写固定值alipay.fund.trans.toaccount.transfer
            'format'    =>  'JSON', //目前仅支持JSON
            'charset'    =>  'UTF-8',
            'sign_type'    =>  'RSA2',//签名方式
            'sign'    =>  '', //签名
            'timestamp'    => date('Y-m-d H:i:s'), //发送时间 格式0000-00-00 00:00:00
            'version'    =>  '1.0', //固定为1.0
            'biz_content'    =>  '', //业务请求参数的集合
        ];
        
        //请求参数
        $api_params = [
            'out_biz_no'  => '20180131141717',//商户转账订单号
        ];
        $pub_params['biz_content'] = json_encode($api_params,JSON_UNESCAPED_UNICODE);
        $pub_params =  $this->setRsa2Sign($pub_params);
      
       return $this->curlRequest(self::TRANSFER, $pub_params);
    }
	//转账
    public function transfer($data){
        //公共请求参数
         $pub_params = [
            'app_id'    => self::APPID,
            'method'    =>  'alipay.fund.trans.toaccount.transfer', //接口名称 应填写固定值alipay.fund.trans.toaccount.transfer
            'format'    =>  'JSON', //目前仅支持JSON
            'charset'    =>  'UTF-8',
            'sign_type'    =>  'RSA2',//签名方式
            'sign'    =>  '', //签名
            'timestamp'    => date('Y-m-d H:i:s'), //发送时间 格式0000-00-00 00:00:00
            'version'    =>  '1.0', //固定为1.0
            'biz_content'    =>  '', //业务请求参数的集合
        ];
        
        //请求参数
        $api_params = [
            'out_biz_no'  => date('YmdHis'),//商户转账订单号
            'payee_type'  => 'ALIPAY_LOGONID', //收款方账户类型 
            'payee_account'  => $data['payee_account'], //收款方账户
            'amount'  => $data['amount'], //金额
        ];
        $pub_params['biz_content'] = json_encode($api_params,JSON_UNESCAPED_UNICODE);
        // var_dump($pub_params['biz_content']);die;
        $pub_params =  $this->setRsa2Sign($pub_params);
      
       return $this->curlRequest(self::TRANSFER, $pub_params);
    }
}

//构建支付请求 可以传递MD5 RSA RSA2三种参数
$obj = new Alipay();

$data = [
     'payee_account'  => '153130605', //收款方账户
     'amount'  => '0.1', //金额
];

//UTF-8格式的json数据
$res = iconv('gbk','utf-8',$obj->transfer($data));


echo '<pre>';
//转换为数组
$res = json_decode($res,true); 

print_r($res);

/*
$res = $obj->searchPay();
print_r(json_decode($res,true));
*/