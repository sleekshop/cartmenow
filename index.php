<?php
//ini_set("display_errors", "on");
//ini_set("display_startip_errors", "on");
//error_reporting( E_ALL );
require_once __DIR__.'/vendor/autoload.php';
$loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
$twig = new Twig_Environment($loader);
$twig->addExtension(new Twig_Extensions_Extension_I18n());
include("./sleek_cfg.inc.php");
include("./vendor/sleekcommerce/init.inc.php");
$request = $_SERVER['REQUEST_URI'];
//Important for CORS
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
$request=explode("/",$request);
$request=array_pop($request);
$request="/".$request;
switch ($request) {
    case '/' :
        require __DIR__ . '/views/index_tpl.php';
        break;
    case '' :
        require __DIR__ . '/views/index_tpl.php';
        break;
    case '/get-cart' :
        $session=$_POST["session"];
        $res=CartCtl::Get($session);
        echo $twig->render('cart.html', array("res"=>$res));
        break;
    case '/add-to-cart' :
        $session=$_POST["session"];
        $id_product=$_POST["id_product"];
        $quantity=1;
        $res=CartCtl::Add($session,$id_product,$quantity,"price","name","short_description",$language,"PRODUCT",0,array(array("lang"=>$language,"name"=>"pic","value"=>$pic)));
        echo $twig->render('cart.html', array("res"=>$res));
        break;
    case '/decrease-qty' :
        $session=$_POST["session"];
        $id_element=$_POST["id_element"];
        $res=CartCtl::Del($session,$id_element);
        echo $twig->render('cart.html', array("res"=>$res));
        break;
    case '/your-data' :
        $session=$_POST["session"];
        $res=UserCtl::GetUserData($session);
        if($res["username"]!="")
         {
          echo $twig->render('userdata.html',array("userdata"=>$res));
         }
        else {
          echo $twig->render('your_data.html', array());
          }
        break;
    case '/login' :
        echo $twig->render('login.html');
        break;
    case '/register' :
          echo $twig->render('register.html');
          break;
    case '/guest' :
          echo $twig->render('userdata.html');
          break;
    case '/login-user' :
        $session=$_POST["session"];
        $username=$_POST["username"];
        $passwd=$_POST["password"];
        $res=UserCtl::Login($session,$username,$passwd);
        if($res['status']=="SUCCESS")
         {
          $username=$res["username"];
          setcookie("username",$username);
          $res=UserCtl::GetUserData($session);
          $profile=$_POST["profile"];
          if($profile!=1)
           {
            echo $twig->render('userdata.html',array("userdata"=>$res));
           }
          else {
           $res=UserCtl::GetUserOrders($session);
           echo $twig->render('profile.html',array("res"=>$res));
           }
          }
          else {
           echo $twig->render('login.html',array("error"=>1,"res"=>$res));
          }
        break;
    case '/register-user' :
         $email=$_POST["email"];
         $passwd1=$_POST["password"];
         $passwd2=$_POST["password2"];
         $user=$email;
         $error=0;
  	     $res=UserCtl::RegisterUser(array("username"=>$user,"email"=>$email,"passwd1"=>$passwd1,"passwd2"=>$passwd2),$language);
  	     if($res["status"]=="SUCCESS")
  	      {
  		     UserCtl::VerifyUser($res["id_user"],$res["session_id"]);
  		     echo $twig->render("login.html",array());
  	      }
  	     else
  	      {
  		     $error++;
  		     $error_msg=$res["status"];
           echo $twig->render("register.html",array("error_msg"=>$error_msg,"error"=>1,"user"=>$user,"email"=>$email));
  	      }
        break;
    case '/userdata' :
       $session=$_POST["session"];
       $error=array();
       $error_count=0;
       $salutation=$_POST["salutation"];
       $firstname=$_POST["firstname"];
       $lastname=$_POST["lastname"];
       $company=$_POST["company"];
       $department=$_POST["department"];
       $street=$_POST["street"];
       $number=$_POST["number"];
       $zip=$_POST["zip"];
       $city=$_POST["city"];
       $state=$_POST["state"];
       $country=$_POST["country"];
       $notes=$_POST["notes"];
       $email=$_POST["email"];
       $userdata["salutation"]=$salutation;
       $userdata["firstname"]=$firstname;
       $userdata["lastname"]=$lastname;
       $userdata["company"]=$company;
       $userdata["department"]=$department;
       $userdata["street"]=$street;
       $userdata["number"]=$number;
       $userdata["zip"]=$zip;
       $userdata["city"]=$city;
       $userdata["state"]=$state;
       $userdata["country"]=$country;
       $userdata["notes"]=$notes;
       $userdata["email"]=$email;
       $args=array("delivery_companyname"=>$userdata["company"],"delivery_department"=>$userdata["department"],"delivery_salutation"=>$userdata["salutation"],
      "delivery_firstname"=>$userdata["firstname"],"delivery_lastname"=>$userdata["lastname"],"delivery_street"=>$userdata["street"],"delivery_number"=>$userdata["number"],"delivery_zip"=>$userdata["zip"],
      "delivery_state"=>$userdata["state"],"delivery_city"=>$userdata["city"],"delivery_country"=>$userdata["country"],
      "invoice_companyname"=>$userdata["company"],"invoice_department"=>$userdata["department"],"invoice_salutation"=>$userdata["salutation"],
      "invoice_firstname"=>$userdata["firstname"],"invoice_lastname"=>$userdata["lastname"],"invoice_street"=>$userdata["street"],"invoice_number"=>$userdata["number"],"invoice_zip"=>$userdata["zip"],
      "invoice_state"=>$userdata["state"],"invoice_city"=>$userdata["city"],"invoice_country"=>$userdata["country"],"note"=>$userdata["note"],"email"=>$userdata["email"]);
      $order_data=OrderCtl::SetOrderDetails($session,$args);
      if($email=="") $error["email"]="has-error";
      if($firstname=="") $error["firstname"]="has-error";
      if($lastname=="") $error["lastname"]="has-error";
      if($street=="") $error["street"]="has-error";
      if($number=="") $error["number"]="has-error";
      if($zip=="") $error["zip"]="has-error";
      if($city=="") $error["city"]="has-error";
      if($country=="") $error["country"]="has-error";
      if(count($error)!=0)
       {
        $error_count++;
        echo $twig->render('userdata.html',array("userdata"=>$userdata,"error"=>$error,"error_count"=>$error_count));
       }
     else {
        $args=array("companyname"=>$userdata["company"],"department"=>$userdata["department"],"salutation"=>$userdata["salutation"],
        "firstname"=>$userdata["firstname"],"lastname"=>$userdata["lastname"],"street"=>$userdata["street"],"number"=>$userdata["number"],"zip"=>$userdata["zip"],
        "state"=>$userdata["state"],"city"=>$userdata["city"],"country"=>$userdata["country"],
        "email"=>$userdata["email"]);
        UserCtl::SetUserData(SessionCtl::GetSession(),$args);
        $payment_methods=PaymentCtl::GetPaymentMethods();
        echo $twig->render("payment_methods.html",array("payment_methods"=>$payment_methods,"host"=>$_SERVER["HTTP_HOST"]));
      }
      break;
    case '/order-summary' :
       $session=$_POST["session"];
       $token=$_POST["token"];
       $id_payment=$_POST["id_payment"];
       $order=OrderCtl::SetOrderDetails($session,array("id_payment_method"=>$id_payment,"id_delivery_method"=>1));
       $cart=CartCtl::Get($session);
       echo $twig->render('order_summary.html',array("order"=>$order,"token"=>$token,"cart"=>$cart));
    break;
    case '/checkout' :
     $session=$_POST["session"];
     $cart=CartCtl::Get($session);
     $token=$_POST["token"];
     $delivery_costs=array(array("Delivery",$cart["delivery_costs"]["sum"],0.19));
     OrderCtl::AddDeliveryCosts($session,$delivery_costs);
     $res=OrderCtl::Checkout($session);
     if($res["status"]=="success")
      {
       $order=OrderCtl::GetOrderDetails($session);
       $subject="Danke, wir haben Ihre Bestellung erhalten";
       $msg="Vielen Dank, wir haben Ihre Bestellung erhalten.\n\n";
       $msg.="Folgende Produkte haben Sie bestellt:\n\n";
       foreach($cart["contents"] as $e)
        {
         $msg.= "Artikel-ID:" . $e["id_product"] . "\n" . $e["name"] . "\n". $e["description"] . "\n". "Anzahl:" . $e["quantity"] . "\nPreis: " .number_format($e["price"],2) .  " EUR\nSumme: " . number_format($e["sum_price"],2) . " EUR\n";
         $msg.="-----------------------------------------------------------------\n";
        }
         $msg.="Summe: " . number_format($cart["sum"],2) . " EUR\n\n";
         $msg.="Bezahlung: " . $order["order_payment_method"]."\n\n";
         $msg.="Lieferung: " . $order["order_delivery_method"]."\n\n";
         $msg.="Ihre Daten:\n";
         $msg.=$order["delivery_salutation"] . " " . $order["delivery_firstname"] . " " . $order["delivery_lastname"] . "\n";
         $msg.=$order["delivery_street"] . " " . $order["delivery_number"] . "\n";
         $msg.=$order["delivery_zip"] . " " . $order["delivery_city"] . " " . $order["delivery_country"] . "\n";
         $msg.="E-Mail: " . $order["email"] . "\n";
         $msg.="Anmerkungen:\n".$order["notes"];
    //send_plain_mail($order["email"],utf8_decode($subject),utf8_decode($msg),ORDER_SENDER);
    //send_plain_mail("info@justgreatbread.com",utf8_decode($subject),utf8_decode($msg),ORDER_SENDER);
   /*
    * End of email - sending
    */
         $id_order=$res["id_order"];
         $session=$res["session"];
         SessionCtl::SetSession($session);
         $cart=array();
         $res=OrderCtl::DoPayment($id_order,array());
         if($res["status"]=="Success" AND $res["redirect"]!=NULL)
          {
            $redirect=html_entity_decode($res["redirect"]);
          }
     }
        echo $twig->render("checkout.html",array("res"=>$res,"token"=>$token,"redirect"=>$redirect));
    break;
    case '/getsession' :
       $session=SessionCtl::GetSession();
       echo $twig->render('get_session.html',array("session"=>$session));
    break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404_tpl.php';
        break;
}
