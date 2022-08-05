<?php
class HttpPostType{
    public static $JSON = "json";
    public static $QUERBUILDQUERY = "buildquery";
}

class Http{
    private $_response;

    public $scraper = false;
    public $scraper_uri;
    
    private $_proxy,$_proxyauth;

    private $_headers = [],$_options = [],$_flash_options = [];
 
    public function setHeaders($headers = []){
        $this->_headers = $headers;
    }
  
    public function proxy($proxy){
      $this->_proxy = trim($proxy);
      return $this;
    }
    public function proxyauth($auth){
      $this->_proxyauth = $auth;
      return $this;
    }

    public function userAgent(string $useragent){
      $this->_options[CURLOPT_USERAGENT] = $useragent;
    }

    public function setFlashOption($option,$value){
      $this->_flash_options[$option] = $value;
    }
    public function clearFlashOptions(){
      $this->_flash_options = [];
    }

    private function request($type,$url,$headers = [],$data = ''){
        $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);

      if($type == 'POST' || $type == 'PUT'){
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
      }

      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36 OPR/71.0.3770.456');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 5);
      curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
      
      curl_setopt($ch,CURLOPT_COOKIE,'_ga=GA1.2.205373952.1658760285; __gads=ID=41efee2134190067:T=1658760286:S=ALNI_MaMBeWw__e2HxRn5akcIRJzMIfYmQ; __gpi=UID=00000923e7e4b070:T=1658760286:RT=1658760286:S=ALNI_Malf9U-Oe5Kz1QLVuNJYwBOafXoww; bq_sd=%7B%22abg%22%3A%22a%22%2C%22bqPvd%22%3A20%7D; cf_chl_2=020e9e053db12c5; cf_chl_prog=x15; cf_clearance=ih2Thu6JTQmgWFjpu11GUO78G5P.USbjBMDHuoL5Eqc-1658770048-0-150; __cf_bm=wo1RavrsgDjHYTxe_ZCcbfbV.As_u2dhg_bCKHttllk-1658770049-0-AY5Vzh5VoIrog+EqpFF19nstJhMQpY1gI2iUUK/xn4InycL3F+LjIb5qixK8ZGwxnI1ZY5/3+poo0//7w/ZJe+eHuw3xqDDjRksTK4MOFCSlleF7iSrukD9Glv54Lm09Xw==; _gid=GA1.2.1535999692.1658770127; _gat_gtag_UA_6252296_1=1');
  
      if(!empty($this->_proxy)){
        curl_setopt($ch, CURLOPT_PROXY, $this->_proxy);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
      }
      if(!empty($this->_proxyauth)){
        curl_setopt($ch, CURLOPT_TIMEOUT, 200);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->_proxyauth);
      }


  
      curl_setopt($ch,CURLOPT_AUTOREFERER,1);
  
      curl_setopt($ch, CURLOPT_HEADER, 1);


      if(count($this->_headers) > 0 ){
        $list = array_merge($this->_headers,$headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$list);
      }else{
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      }

      if(count($this->_options) > 0 ){
        foreach($this->_options as $key => $value){
          curl_setopt($ch, $key, $value);
        }
      }
      if(count($this->_flash_options) > 0){
        foreach($this->_flash_options as $key => $value){
          curl_setopt($ch,$key,$value);
        }
      }
      

      $response = curl_exec($ch);
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
      curl_close($ch);
  
      $this->_response = new HttpResponse();
      $this->_response->setStatus($httpcode);
      $this->_response->setHeader( substr($response, 0, $header_size) );
      $this->_response->setBody(substr($response, $header_size));
  

      // all time clear flash options
      // usually use one time
      $this->clearFlashOptions();

      $this->_proxy = null;
      $this->_proxyauth = null;

      return $this->response();
    }
  
    public function get(string $url,array $headers = []){
        if($this->scraper === true){
          return $this->post($this->scraper_uri,["site"=>$url],$headers);
        }
        return $this->request("GET",$url,$headers);
    }


    public function post(string $url,$data, $headers = [],$type = HttpPostType::QUERBUILDQUERY){

        if($type == 'json'){

            $headers[] = 'Content-Type:application/json';

            return $this->request("POST",$url,$headers,json_encode($data));

        }

        return $this->request("POST",$url,$headers,http_build_query($data));
   }
   public function put(string $url,$data, $headers = [],$type = HttpPostType::QUERBUILDQUERY){

    if($type == 'json'){

        $headers[] = 'Content-Type:application/json';

        return $this->request("PUT",$url,$headers,json_encode($data));

    }

    return $this->request("PUT",$url,$headers,http_build_query($data));
   }
   public function delete($url,$headers = []){
        return $this->request("DELETE",$url,$headers);
   }

   public function download($image_url,$save_path){
      $fp = fopen($save_path,"w+");
      $ch = curl_init($image_url);
      curl_setopt($ch, CURLOPT_FILE, $fp);        
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_TIMEOUT, 1000);    
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
      curl_exec($ch);
      curl_close($ch);          
      fclose($fp);
   }


    protected function response(){
      return $this->_response;
    }
  
  }