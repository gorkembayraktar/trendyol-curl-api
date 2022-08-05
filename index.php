<?php

require 'trendyolCore/init.php';



$trendyol = new Trendyol();

$username = "mail_adresi@gmail.com";
$password = "password";
$proxy = null;
$proxyPassword = null;

$login = $trendyol->login($username,$password,$proxy,$proxyPassword);


if($trendyol->isLogin()){
    echo 'evet';
}


//

//$login = $trendyol->reLogin($username,$password,$proxy,$proxyPassword);



//$trendyol->user->getEmail();


//print_r( $trendyol->account->myOrders() );


//print_r( $trendyol->account->myAssessments() );


//print_r($trendyol->account->userInfo());






//print_r($trendyol->account->collections());




//$collection_name = "hello world";
//print_r($trendyol->account->create_collection($collection_name));

/*$collection_name = "hello world 2 asdad";
$collection_id = "7176f458-e9f0-40a3-81bd-e5d71126e336";

$trendyol->account->rename_collection($collection_name,$collection_id);
*/

/*
$collection_id = "77958e71-21b6-475d-96da-466ccb7f0d0b";

$isOk = $trendyol->account->remove_collection($collection_id);
*/


/*

$product_contentIds = ["276246407"];
$collection_id = "0b9465a8-85e3-4724-a0eb-1bce3354ebd2";

$isOk = $trendyol->account->add_product_collection($collection_id,$product_contentIds);
*/



//print_r($trendyol->product->favorites());

//print_r($trendyol->product->add_favorite("46778114"));

//print_r($trendyol->product->remove_favorite("46778114"));

/*
$product_contentIds = ["276246407"];
$contentId = "276246407";

$isOk = $trendyol->product->add_basket($contentId,1);
*/

/*
$contentId = "37206082";
$product_info = $trendyol->product->info($contentId);
$variant = $product_info["variants"][0]["listingId"];


$quantity = 1;

$isOk = $trendyol->product->add_basket($contentId,$variant,$quantity);
*/
/*
function pretty_info($data){
    echo "<pre>".print_r($data,true)."</pre>";
}
*/

/*
$itemId = "276246407-fdfea7050e8866256cc9a972f4dd88c5-61";
$quantity = 2;
$isOk = $trendyol->product->update_quatity_basket($itemId,$quantity);
*/


/*

$basket_list = $trendyol->product->get_basket();

print_r($basket_list);

*/


/*
$basket_id = "276246407-fdfea7050e8866256cc9a972f4dd88c5-61";

$x = $trendyol->product->remove_basket($basket_id);

pretty_info($x);

*/