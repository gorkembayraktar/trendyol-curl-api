<?php

class TrendyolMessages{
    private $messages = [];
    protected function log($message){

        $this->_log(date('Y-m-d H:i:s') . "\t" . $message);

        array_push($this->messages,[
            "date" => date("Y-m-d H:i:s"),
            "message" => $message
        ]);
    }
    protected function getMessages(){
        return $this->messages;
    }
    protected function lastMessage(){
        return end($this->messages);
    }

    private function _log($message){

        $logFile = FileHelper::$folder."log.txt";

        $f = fopen($logFile,"a");

        fwrite($f,$message."\n");

        fclose($f);
    }
}