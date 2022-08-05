<?php


class TrendyolProduct{

    private $token,$http;

    public function __construct($http){
        $this->http = $http;
    }
    public function setToken($token){
        $this->token = $token;
    }

    private function _favorites(){

        // hatalı..
        $response = $this
        ->http
        ->get(
            TrendyolAPI::$account_favorites,
            ["authorization: ".$this->token]
        );

        if($response->status() === 200){
            //başarılı
            $data = json_decode($response->body(),true);
            return $data['result'];
        }else{
            $data = json_decode($response->body(),true);
            return $data;
        }
    }

    private function _add_favorite($contentId){

        $response = $this
        ->http
        ->post(
            TrendyolAPI::$account_add_favorite,
            ["contentId" => $contentId],
            ["authorization: ".$this->token],
            HttpPostType::$JSON
        );

        if($response->status() === 200){
            //başarılı
            $data = json_decode($response->body(),true);
            return $data['isSuccess'];
        }else{
         
            return false;
        }
    }

    private function _remove_favorite($id){

        $response = $this
        ->http
        ->delete(
            TrendyolAPI::account_remove_favorite($id),
            ["authorization: ".$this->token]
        );

   
        if($response->status() === 200){
            //başarılı
            $data = json_decode($response->body(),true);
            return $data['result'];
        }else{
            $data = json_decode($response->body(),true);
            return $data;
        }
    }

    public function _info($product_id){
        $response = $this
        ->http
        ->get(
            TrendyolAPI::product_info($product_id)
        );

        if($response->status() === 200){
            //başarılı
            $data = json_decode($response->body(),true);
            return $data['result'];
        }else{
            $data = json_decode($response->body(),true);
            return $data;
        }
    }

    public function _add_basket($contentId,$variant,$quantity = 1){
        $post = [
            "listingId" => $variant,
            "quantity" => $quantity,
            "contentId" => $contentId,
            "vasItems" =>  []
        ];

        $response = $this
        ->http
        ->post(
            TrendyolAPI::$product_basket_add,
            $post,
            ["authorization: ".$this->token],
            HttpPostType::$JSON
        );

        if($response->status() === 200){
            //başarılı
            $data = json_decode($response->body(),true);
            return $data['isSuccess'];
        }else{
         
            return false;
        }
    }

    public function _update_quatity_basket($itemId,$quantity){
        $post = [
            "itemId" => $itemId,
            "quantity" => $quantity
        ];

        $response = $this
        ->http
        ->put(
            TrendyolAPI::$update_basket,
            $post,
            ["authorization: ".$this->token],
            HttpPostType::$JSON
        );

        $d = $response->status() === 204  ? true :  json_decode($response->body(),true);
    
        return $d;
    }

    public function _get_basket(){
 

        $response = $this
        ->http
        ->get(
            TrendyolAPI::$update_basket_data,
            ["authorization: ".$this->token]
        );

        if($response->status() === 200){
            //başarılı
            $data = json_decode($response->body(),true);
            return $data['result']['data'];
        }else{
            $data = json_decode($response->body(),true);
            return $data;
        }
    

    }

    public function _remove_basket($itemId){
 

        $post = [
            "itemId" => $itemId
        ];

        $response = $this
        ->http
        ->post(
            TrendyolAPI::$product_remove,
            $post,
            ["authorization: ".$this->token],
            HttpPostType::$JSON
        );

        $d = $response->status() === 204  ? true :  json_decode($response->body(),true);
        return $d;

    }

    public function __call($name, $arguments)
    {
        if($this->token == null){
            return 'token not defined';
        }

        $myfunc = '_'.$name;

        if (method_exists($this,$myfunc)) {
            return $this->$myfunc(...$arguments);
        }else{
            die("$name method not defined");
        }
     
    }

}