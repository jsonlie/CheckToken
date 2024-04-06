<?php
namespace Jsonlie\CheckToken;


class Token
{
    //请求参数
    private $params = [];
    //无需要验证的请求参数
    private $no_auth = [];
    //验证密钥
    private $secret;

    private static $instance = null;

    private function __construct(){}

    /**
     * 获取单例对象
     */
    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __set($name,$value){
        $this->$name = $value;
    }

    /**
     * 验证请求token
     * @return bool
     */
    public function check(){
        $params = $this->params;
        $no_auth = $this->no_auth;
        if($no_auth){
            foreach($no_auth as $key => $value){
                if(isset($params[$key])){
                    unset($params[$key]);
                }
            }
        }
        $token = $params['token'] ?? '';
        if(empty($token)){
            return false;
        }
        ksort($params);
        $tstr = '';
        foreach($params as $k => $v){
            $tstr .= $k.'='.$v.'&';
        }
        $tstr .= 'secret='.$this->secret;
        $stoken = strtoupper(md5($tstr));
        return $token === $stoken;
    }

}