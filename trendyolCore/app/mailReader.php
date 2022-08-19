<?php

date_default_timezone_set('Europe/Istanbul');

$path = dirname(__DIR__).DIRECTORY_SEPARATOR."helper".DIRECTORY_SEPARATOR."emailReader.php";

require ($path);

 class TrendyolMailReader{

    private $reader;

    //private $lastMinutes = 5;

    public function __construct($server,$user,$pass,$port){

        
        $config = new EmailReaderConfig($server,$user,$pass,$port);



        $this->reader = new EmailReader($config);
        
    }

    public function getActiveCode(){

        $items = $this->reader->unseenMessages();

        $refrest = 10;

        do{
            // 22 32 < 22 36
            if($items){
            
                foreach($items as $last){
            
                    if($last && $last['header']->from[0]->host == TrendyolAPI::$registerConfirmMail){
                        $re = '/[\d]{6}/';
                        $str = $last['title'];
                        
                        preg_match($re, $str, $match);
                        
                        if($match){
                            return $match[0];
                        }
                        // Print the entire match result
                        return '';
                    }

                }
            }
            sleep(2);
            $refrest--;
        }while($refrest > 0);

        return '';

    }

    
}