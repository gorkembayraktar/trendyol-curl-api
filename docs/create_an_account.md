### Hesap Oluşturmak
Hesap oluşturmak için mail servisine ihtiyacınız vardır. host,mail,password ve port bilgisini tanımlamalısınız. IMAP fonksiyonu kullanılmaktadır. Local sunucunuzda default disabled tanımlanır. Erişimi açtığınızda bu methodu kullanabilirsiniz.

       
        $trendyol = new Trendyol();


        $data['email'] = "mail@mailhost.com";
        $data['password'] = "yourpassword";
        $data['gender'] = "man";// man or woman


        //@boolean
        $success = $trendyol->register($data);

        if($success){

            $server = "mail.yourhost.com";
            $user = "test@yourhost.com";
            $pass = ")qsm~&dmpM]b13213";
            $port = "993"; // whatever
            $trendyolMailReader = new TrendyolMailReader($server,$user,$pass,$port);
            
            $code = $trendyolMailReader->getActiveCode();
            
            // @return array & [success@boolean,retryCount@integer]
            $result = $trendyol->mailConfirm($code);

            if($result['success']){
                echo "Successfully";
            }else{
                echo "Retry count : . ".$result['retryCount'];
            }



        }
