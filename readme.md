
## TRENDYOL API V1

> Bu api ' yı kullanabilmek için projenize dahil etmeniz gerekir.
> `require  'trendyolCore/init.php';`


### API INIT İşlemi

    $trendyol = new  Trendyol();
    
### Giriş Bilgileri

    $username = "mail_adresi_ltsadsadsa_dsadsa@gmail.com";
    $password = "yourpassword";
    $proxy = null;
    $proxyPassword = null;
    
  ### Giriş Yapmak
  

> Giriş bilgileri cache altında olduğunu unutmayınız.

    // return @boolean
    $login = $trendyol->login($username,$password,$proxy,$proxyPassword);

### Tekrar Giriş Yapmak ( Bellekten okuma yapmaz )

      $login = $trendyol->reLogin($username,$password,$proxy,$proxyPassword);


### Hesap Oluştur
    TrendyolMailReader sınıfı geliştirildi. 
    Örnek kullanım için dökümana gidiniz.
[Hesap oluştur döküman](/docs/create_an_account.md)

### Promosyon Kodu Kullan
    Geliştiriliyor..


### Kullanıcı İşlemleri
Giriş yapıldıktan sonra verileri okuyabiliriz.
#### Kullanıcı Mail Getir

    $mail = $trendyol->user->getEmail();
 #### Kullanıcı Id Getir
  `$userid = $trendyol->user->getUserId();`


### Hesap İşlemleri
	Hesap işlemlerini yönetmemizi sağlar.
	
#### Siparişlerim

    $orders = $trendyol->account->myOrders();
 #### Değerlendirmelerim
	 
    $assessments = $trendyol->account->myAssessments();
#### Hesap Detaylı Bilgileri

    print_r($trendyol->account->userInfo());

   #### Koleksiyonlarım
   

    print_r($trendyol->account->collections());
#### Koleksiyon Oluştur

    $collection_name = "hello world";
    print_r($trendyol->account->create_collection($collection_name));
#### Koleksiyon Adı Değiştir

    $collection_name = "hello world 2 asdad";
    $collection_id = "523f0831-97b0-476a-89b4-7526070813d41";
    $trendyol->account->rename_collection($collection_name,$collection_id);
   #### Koleksiyon Sil
   

    $collection_id = "77958e71-21b6-475d-96da-466ccb7f0d0b";
    $isOk = $trendyol->account->remove_collection($collection_id);


#### Ürünleri Koleksiyona Eklemek

    $product_contentIds = ["276246407"];
    $collection_id = "0b9465a8-85e3-4724-a0eb-1bce3354ebd2";
    $isOk = $trendyol->account->add_product_collection($collection_id,$product_contentIds);


### Ürün İşlemleri

Ürünlerin datasına erişebilir ve kısıtlı yönetim yapabilirsiniz.

#### Favorilerim

    //print_r($trendyol->product->favorites());
  #### Favorilere Ekle
    $productId = "46778114";
    print_r($trendyol->product->add_favorite($productId));
 #### Favorilerden Çıkart
     print_r($trendyol->product->remove_favorite("46778114"));
    

#### Sepetteki Ürünler

    $basket_list = $trendyol->product->get_basket();
    print_r($basket_list);
	
#### Sepete Ürün ekle

    $contentId = "276246407";
    $quality = 1;
    $isOk = $trendyol->product->add_basket($contentId,$quality);

#### Sepetteki ürünün adet miktarını güncelle

    $itemId = "276246407-fdfea7050e8866256cc9a972f4dd88c5-61";
    $quantity = 2;
    $isOk = $trendyol->product->update_quatity_basket($itemId,$quantity);

#### Sepetteki Ürünü Kaldırmak

    $basket_id = "276246407-fdfea7050e8866256cc9a972f4dd88c5-61";
    $trendyol->product->remove_basket($basket_id);




Aksiyonlar burada sona eriyor , daha fazlası için projeyi yıldızlamayı unutmayınız. İyi çalışmalar.

   
    




   