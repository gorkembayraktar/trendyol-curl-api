<?php


class TrendyolAPI{


    public static $culture = 'tr-TR';

    public static  $login_uri = "https://auth.trendyol.com/login";


    public static $account_orders = "https://public-sdc.trendyol.com/discovery-web-omsgw-service/orders?page=1&sorting=0&storefrontId=1&searchText=";


    public static $account_degerlendirme = "https://public-sdc.trendyol.com/discovery-web-accountgw-service/api/reviews/evaluated/pdp?page=1&reviewStatus=APPROVED";


    public static $account_favorites = "https://public-mdc.trendyol.com/discovery-web-recogw-service/api/favorites?specialFilterType=&pageIndex=0&itemCount=12&storefrontId=1&cacheBuster=1659652291716&culture=tr-TR&isLegalRequirementConfirmed=false&productStampType=TypeA";
    public static function account_info(){
        return "https://public-sdc.trendyol.com/discovery-web-membergw-service/fragment/user-information/Hesabim/KullaniciBilgileri?culture=tr-TR&storefrontId=1";
    }

    public static $account_collections = "https://public-mdc.trendyol.com/discovery-web-recogw-service/api/collections/infinite?type=OWNER&page=0&pageSize=12";

    public static $account_add_favorite = "https://public-mdc.trendyol.com/discovery-web-recogw-service/api/favorites?storefrontId=1&culture=tr-TR";


    public static function account_remove_favorite($id){
        return "https://public-mdc.trendyol.com/discovery-web-recogw-service/api/favorites?contentId=$id&storefrontId=1&culture=tr-TR";
    }

    public static $account_collection_create = "https://public-mdc.trendyol.com/discovery-web-recogw-service/api/collections/create";

    public static function account_collection_update($id){
        return "https://public-mdc.trendyol.com/discovery-web-recogw-service/api/collections/$id";
    }

    public static  $account_colection_delete = "https://public-mdc.trendyol.com/discovery-web-recogw-service/api/collections/delete";


    public static function account_add_product_collection($id){
        return "https://public-mdc.trendyol.com/discovery-web-recogw-service/api/collection/$id?culture=tr-TR&storefrontId=1";
    }
    public static function product_info($product_id){
        return "https://public.trendyol.com/discovery-web-productgw-service/api/productDetail/$product_id?sav=false&storefrontId=1&culture=tr-TR&linearVariants=true&isLegalRequirementConfirmed=false";
    }
    public static $product_basket_add = "https://public-mdc.trendyol.com/discovery-web-checkout-service/api/basket/v2/add?culture=tr-TR&storefrontId=1";

    public static $update_basket = "https://public-mdc.trendyol.com/discovery-web-checkout-service/api/basket/v2/updateItem";

    public static $update_basket_data = "https://public-mdc.trendyol.com/discovery-web-checkout-service/basket/fragment/sepet?culture=tr-TR&storefrontId=1&productSuggestionAbTest=B&cargoRecommendationAbTest=B&pudoBannerImageAbTest=A&totalDiscountCountAsProfitAbTest=C";

    public static $product_remove = "https://public-mdc.trendyol.com/discovery-web-checkout-service/api/basket/v2/removeItem";
}