<?php

class Trendyol extends TrendyolMessages{

    // Geliştirici modunda
    private $devMode = false;

    // request bilgileri.
    private $http;

    // http authriztion bölgelerinde gerekli
    private $Bearer,$Cookie;

    // giriş durumuna göre
    private $loginState = false;

    public $user,$account,$product;
    private $token,$token_detail;


    private $headerConfig = [
        'culture: tr-TR',
        'storefront-id: 1',
        'application-id: 1'
    ];


    function __construct($config = null){
        if($config){
            $this->config($config);
        }
        $this->http = new Http();
        $this->http->setHeaders($this->headerConfig);
        $this->account = new TrendyolAccount($this->http);
        $this->product = new TrendyolProduct($this->http);

    }
    function user(){
        return $this->user;
    }
    function login($username,$password,$proxy = null,$proxyPasword = null){

        if($this->isCache($username)){

            $this->log("LOGIN WITH CACHE :$username");

            $this->loginProps($this->cache($username));

            return true;
        }

        return $this->_login($username,$password,$proxy,$proxyPasword);
    }

    public function relogin($username,$password,$proxy = null,$proxyPasword = null){
        $this->log("RELOGIN:$username");
        return $this->_login($username,$password,$proxy,$proxyPasword);
    }
    private function _login($username,$password,$proxy = null,$proxyPasword = null){

        if($proxy != null)
            $this->http->proxy($proxy);
        if($proxyPasword != null)
            $this->http->proxyauth($proxyPasword);

        $response = $this
        ->http
        ->post(
            TrendyolAPI::$login_uri,
            ["email" => $username,"password"=>$password],
            [],
            HttpPostType::$JSON
        );

        
        
        if($response->status() === 200){
            //başarılı
            $this->cacheIt($username,$response->body());
            $this->loginProps($response->body());

            $this->log("LOGIN:$username");
            return true;
        }else{
            $this->log("LOGIN_ERROR_CODE:{$response->status()}");
            $this->log("LOGIN_ERROR_MESSAGE:{$response->body()}");
            return false;
        }
    }
    function isLogin(){
        return $this->user !== null;
    }

    private function loginProps($data){
        $json = json_decode($data,true);
        $this->token = $json['accessToken'];
        $this->token_detail = $json;

        $this->account->setToken($this->token);
        $this->product->setToken($this->token);
        $this->user = new TrendyolUser(
            json_decode(
                base64_decode(
                    explode('.',$this->token)[1]
                ),
                true
            )
        );

    }



    private function cacheIt($username,$data){
           $path = md5($username);
           return FileHelper::saveTxt($path,$data); 
    }
    private function cache($username){
        $path = md5($username);
        return FileHelper::getTxt($path); 
 }
    private function isCache($username){
        $path = md5($username);
        return FileHelper::existsTxt($path);
    }

    function devMode($status){
        $this->devMode = $status;
        return $this;
    }
    public function config($config){
        if(isset($config['cookieFolder'])){
            FileHelper::folder($config['cookieFolder']);
        }
        $keys = [
            "devMode" => "devMode"
        ];
        foreach($config as $key => $value){
            if(isset($keys[$key])){
                $this->{$keys[$key]} = $value;
            }
        }
    }
    public function __destruct(){
        if( $this->devMode ){
            print_r($this->getMessages());
        }
    }
}
