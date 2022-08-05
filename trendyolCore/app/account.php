<?php


class TrendyolAccount{
    
    private $token,$http;

    public function __construct($http){
        $this->http = $http;
    }


    private function _myOrders(){
     
        $response = $this
        ->http
        ->get(
            TrendyolAPI::$account_orders,
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

    private function _myAssessments(){
        $response = $this
        ->http
        ->get(
            TrendyolAPI::$account_degerlendirme,
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

    private function _userInfo(){

           // hatalı..
        $response = $this
        ->http
        ->get(
            TrendyolAPI::account_info(),
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

    

    private function _collections(){

        $response = $this
        ->http
        ->get(
            TrendyolAPI::$account_collections,
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
    private function _create_collection($name){

   
            $response = $this
            ->http
            ->post(
                TrendyolAPI::$account_collection_create,
                ["collectionName" => $name],
                ["authorization: ".$this->token],
                HttpPostType::$JSON
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

    private function _rename_collection($name,$collectionId){

   
        $response = $this
        ->http
        ->put(
            TrendyolAPI::account_collection_update($collectionId),
            ["collectionName" => $name],
            ["authorization: ".$this->token],
            HttpPostType::$JSON
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

    private function _remove_collection($collectionId){

   
        $response = $this
        ->http
        ->post(
            TrendyolAPI::$account_colection_delete,
            ["collectionId" => $collectionId],
            ["authorization: ".$this->token],
            HttpPostType::$JSON
        );

        if($response->status() === 200){
            //başarılı
            $data = json_decode($response->body(),true);
            return $data['isSuccess'];
        }else{
            $data = json_decode($response->body(),true);
            return $data;
        }
    }

    private function _add_product_collection($collectionId,$product_contentIds){

        
        foreach($product_contentIds as &$product){
            $product = [
                "contentId" => $product
            ];
        }

     

   
        $response = $this
        ->http
        ->post(
            TrendyolAPI::$account_colection_delete,
            $product_contentIds,
            ["authorization: ".$this->token],
            HttpPostType::$JSON
        );

        print_r($response);

        return $response->status() === 200;
    }




    public function setToken($token){
        $this->token = $token;
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