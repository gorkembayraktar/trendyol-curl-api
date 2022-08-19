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


    // if created an account then use the props 
    private $register_props;


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


    protected $test_cookie = '';

    public function register($data = array()){

        // gender man = 1 & woman = 0

        $props = [
            "email" => $data['email'],
            "genderId" => $data['gender'] == 'woman' ? 0 : 1,
            "password" => $data['password'],
            "marketingEmailsAuthorized" => false,
            "conditionOfMembershipApproved" => true,
            "protectionOfPersonalDataApproved" => true,
            "anonToken" =>  $this->_register_anon_token()
        ];

    

        $response = $this
        ->http
        ->post(
            TrendyolAPI::$register,
            $props,
            ["culture: tr-TR","storefront-id: 1","application-id: 1"],
            HttpPostType::$JSON
        );

        if($response->status() === 200){
            
            $this->register_props = $props;

            $json = $response->json();
            $message = property_exists($json,'message') ? $json->message : '';
            $this->log("Kayıt oluşturuldu. [$data[email]][message:$message]");

            $this->test_cookie = $response->getCookie();
            $this->log("Cookie ".$response->getCookie());

           return true;
        }else{
            $this->log("Hata.HTTP[{$response->status()}].[{$response->body()}]");
        }

    }

    public function mailConfirm($code){
        if(empty($code)) return ['status' => false,'message' => 'code not empty'];

        if(!$this->register_props){
            return false;
        }

        $this->register_props['otpCode'] = (int)$code;

        $this->http->setFlashOption(CURLOPT_HTTPHEADER ,array(
            'culture: tr-TR',
            'storefront-id: 1',
            'application-id: 1',
            'Content-Type: application/json'
          )
        );

        $response = $this
        ->http
        ->post(
            TrendyolAPI::$register,
            $this->register_props,
            [],
            HttpPostType::$JSON
        );

        $this->log("HTTP[{$response->status()}]".$response->body());
        if($response->status() === 412){
            
            // invalid otp
            $this->log("Hata. Geçersiz otp kodu");

            $result = $response->json();
            return [
                "success" => false,
                "retryCount" => $result->retryCount
            ];
        }else if($response->status() === 400){
            
            $mail = $this->register_props['email'];
            $this->log("Kayıt tamamlandı mail onaylı [$mail ]");

            return [
                "success" => true
            ];
        }

        
        return [
            "success" => false,
            "retryCount" => 0
        ];

    }

    private function _register_anon_token(){
        $atwrtmk = $this->_register_anon_atwrtmk();
        $atwrtmk_md5 = md5($atwrtmk);
        $fiveyears = strtotime('+5 years');
        $now = time();
        $aud = $this->_register_aud();

        $props =  <<< ENDHEREOK
        {
            "urn:trendyol:anonid": "$atwrtmk_md5",
            "role": "anon",
            "atwrtmk": "$atwrtmk",
            "aud": "$aud",
            "exp": $fiveyears,
            "iss": "auth.trendyol.com",
            "nbf": $now
          }
        ENDHEREOK;

        $startwith  = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.";
        $startwith .= base64_encode(trim($props));
        $startwith .= ".F-aFazgnFG6-KrOjgL4vFFvyg-rm9C2a_0qngN0p8Pw";

        return $startwith;
    }
    private function _register_aud(){
        $format = '00000000+00000000000000000000000';
        $letters = "1234567890abcdefgioprstuvyz";
        $letters_count = strlen($letters);
        for($i = 0 ; $i < strlen($format);$i++){
            if( $format[$i] != '+'){
                $format[$i] = $letters[rand(0,$letters_count-1)];
            }
        }
        return $format;
    }

    private function _register_anon_atwrtmk(){
        $format = "f062f369-0000-0000-0000-000000000000";
        $letters = "1234567890abcdefgioprstuvyz";
        $letters_count = strlen($letters);
        for($i = 9 ; $i < strlen($format);$i++){
            if($format[$i] != '-'){
                $format[$i] = $letters[rand(0,$letters_count-1)];
            }
        }
        return $format;
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
