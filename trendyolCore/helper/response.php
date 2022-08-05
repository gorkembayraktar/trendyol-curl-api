
<?php


class HttpResponse{
    private $_body,$_status,$_header;

    public function status(){
      return $this->_status;
    }
    public function body(){
      return $this->_body;
    }
    public function header(){
      return $this->_header;
    }
    public function setStatus(int $status){
       $this->_status = $status;
    }
    public function setBody(string $body){
      $this->_body = $body;
    }
    public function setHeader(string $header){
      $this->_header = $header;
    }
    public function getCookie(){
        if(!$this->_header) return "";
        $headers = $this->cookie_parse($this->_header);

        $setcookie = "set-cookie";
        $cookobjs = Array();
        foreach($headers as $v){

            if (strpos(strtolower($v),$setcookie) !== false){
                $a = explode(':',$v);
                $str = substr($a[1],0,strpos($a[1],";"));
               
                $a = explode('=',$str);
                $cookobjs[$a[0]] = $a[1];
            }
        }

        
        return $this->join($cookobjs);

    }
    private function join($f){
        $str = "";
        foreach($f as $key => $val){
            $str .= $key."=".$val.";";
        }
        return $str;
    }
    private function cookie_parse($data){
        return explode(PHP_EOL,$data);
    }
}
