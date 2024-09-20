<?php
namespace KenNebula\DingerPaymentIntegration;

use ErrorException;
use Illuminate\Support\Facades\Http;

class Dinger {
    public static function pushPayment($data)
    { // precreate response

        $app_type = config('kbz.APP_TYPE');
        $redirect_url = config('kbz.'.$app_type.'.redirect_url');

        $app_id = config('kbz.appid');
        $merch_code = config('kbz.merch_code');
        $nonce_str = self::generateNonce(); // $data['nonce_str']; // use new one or existing 
        $prepay_id = $data['prepay_id'];
        $sign = $data['sign'];
        $timestamp = time();

        // dump($sign);

        $final_url = $redirect_url.'?appid='.$app_id.'&merch_code='.$merch_code.'&nonce_str='.$nonce_str.'&prepay_id='.$prepay_id.'&timestamp='.$timestamp.'&sign='.$sign;
        dd($final_url);

        $response = Http::withHeaders([
            "Content-Type" => "application/json"
        ])->post($final_url);
        
        return $response->json();
        
    }

    public static function precreatePayment($biz_content,$device_type)
    {
        $url = config('kbz.'.env('APP_TYPE', 'uat').'.precreate');

        $paymentRequest = self::buildPaymentRequest($biz_content,$device_type);
        $response = Http::withHeaders([
            "Content-Type" => "application/json"
        ])->post($url, $paymentRequest);

        Helper::save_logs('Precreate Response from payment provider '. self::$provider. '.', $response);       
        return $response->json();
    }

    public static function buildPaymentRequest($biz_content,$device_type)
    {
        $app_id = config('kbz.appid');
        $merch_code = config('kbz.merch_code');
        $trade_type = config('kbz.trade_type.'.$device_type);
        
        $app_key = config('kbz.app_key');
        $method = config('kbz.method');
        $notify_url = config('kbz.notify_url');
        $version = config('kbz.version');

        // dd($app_key,$method,$notify_url,$version);

        $paymentRequest = self::paymentRequest($method,$notify_url,$version);

        $bizContent = self::buildBizContent($biz_content,$app_id,$merch_code,$trade_type);

        $signKeyVal = array_merge($paymentRequest, $bizContent);

        $sign = self::signature(self::joinKeyValue($signKeyVal), $app_key);

        $paymentRequest["sign"] = $sign;

        $paymentRequest["sign_type"] = "SHA256";

        $paymentRequest["biz_content"] = $bizContent;

        // add outer 'Request' field
        $requestWrapper = array();
        $requestWrapper["Request"] = $paymentRequest;

        return $requestWrapper;
    }

    public static function signature($text, $merchantKey)
    {
        $toSignString = $text . "&key=" . $merchantKey;

        return strtoupper(hash("sha256", $toSignString));
    }

    public static function joinKeyValue(array $arr)
    {
        $notEmpty = function ($val) {
            return !empty($val) && trim($val) != "";
        };

        $solidArray = array_filter($arr, $notEmpty);

        ksort($solidArray);

        $joinKeyVal = function (&$val, $key) {
            $val = "$key=$val";
        };

        array_walk($solidArray, $joinKeyVal);

        return implode("&", $solidArray);
    }

    public static function paymentRequest($method,$notify_url,$version)
    {
        $paymentRequest = array();
        $paymentRequest['timestamp'] = time();       
        $paymentRequest['method'] = $method;
        $paymentRequest['notify_url'] = $notify_url;// need to add
        $paymentRequest['nonce_str'] = self::generateNonce();
        $paymentRequest['version'] = $version;

        return $paymentRequest;
    }
    public static function buildBizContent($biz_content,$app_id,$merch_code,$trade_type)
    {
        $bizContent = array();

        $bizContent['appid'] = $app_id;
        $bizContent['merch_code'] = $merch_code;
        $bizContent['trade_type'] = $trade_type;
        $bizContent['trans_currency'] = $biz_content['trans_currency'];
        $bizContent['timeout_express'] = $biz_content['timeout_express'];
        $bizContent['merch_order_id'] = $biz_content['merch_order_id'];
        $bizContent['total_amount'] = $biz_content['total_amount'];;

        return $bizContent;
    }

    public static function generateNonce()
    {
        return uniqid();
    }

}