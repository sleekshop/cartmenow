<?php

/*
 * Init - file
 *
 * @ Kaveh Raji <kr@sleekcommerce.com>
 */
 define("ROOTPATH", ".");
 define("PROJECTPATH", ROOTPATH . "/");
 /*
  * Now including some libaries needed
  */
  include(PROJECTPATH . "vendor/sleekcommerce/conf.inc.php");
  include(PROJECTPATH . "vendor/sleekcommerce/sleekshop_request.inc.php");
  include(PROJECTPATH . "vendor/sleekcommerce/cart.inc.php");
  include(PROJECTPATH . "vendor/sleekcommerce/shopobjects.inc.php");
  include(PROJECTPATH . "vendor/sleekcommerce/categories.inc.php");
  include(PROJECTPATH . "vendor/sleekcommerce/session.inc.php");
  include(PROJECTPATH . "vendor/sleekcommerce/user.inc.php");
  include(PROJECTPATH . "vendor/sleekcommerce/order.inc.php");
  include(PROJECTPATH . "vendor/sleekcommerce/payment.inc.php");
  include(PROJECTPATH . "vendor/sleekcommerce/mailing.inc.php");

/*
* Setting the language
*/
if(!isset($_COOKIE[TOKEN."_lang"]))
 {
   setcookie(TOKEN."_lang",DEFAULT_LANGUAGE,time()+3600);
   $language=DEFAULT_LANGUAGE;
 }
 else {
   $language=$_COOKIE[TOKEN.'_lang'];
 }

putenv('LC_MESSAGES='.$language);
setlocale(LC_MESSAGES, $language);
bindtextdomain($language, PROJECTPATH.'var/languages');
bind_textdomain_codeset($language, 'UTF-8');
textdomain($language);
?>
