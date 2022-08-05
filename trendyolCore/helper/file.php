<?php


class FileHelper{
    public static $folder = 'save\\';
    public static $logFolder = '';
    public static function folder($path){
      FileHelper::$folder = $path;
    }
    public static function saveTxt($filename,$body){
        file_put_contents(FileHelper::$folder.$filename.".txt",$body);
    }
    public static function getTxt($filename){
        return file_get_contents(FileHelper::$folder.$filename.".txt");
    }
    public static function exists($filename){
        return file_exists(FileHelper::$folder.$filename);
    }
    public static function existsTxt($filename){
        return file_exists(FileHelper::$folder.$filename.".txt");
    }
    public static function remove_token($token){
      $file = FileHelper::token($token);
      return FileHelper::remove(FileHelper::$folder.$file);
    }
    public static function token($token){
      return md5($token).".txt";
    }
    public static function remove($filename){
        if(file_exists($filename))
          return !!unlink($filename);
    }

    
    
}