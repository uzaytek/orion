<?php

//.:/usr/share/php:/usr/share/pear
ini_set('include_path', '.:/usr/share/php:/usr/share/pear:/var/www/orion/includes/payment/paypal420/lib/');


if(!defined('ENVIRONMENT')) {
  define('ENVIRONMENT', 'DEVELOPMENT');
}

if(!defined('IN_ADMIN_PANEL')) {
  define('IN_ADMIN_PANEL', FALSE);
}

require_once 'config.php';

/* ---[ INCLUDE FILES ]------------------------------- */
require_once PT_INCLUDE . 'ON_Dao.php';
require_once PT_INCLUDE . 'ON_Utils.php';

/* ---[ SESSION ]------------------------------- */

$session =& ON_Session::instance();

/* ---[ LANGUAGE ]------------------------------- */

putenv("LANG=en_US.utf8");
putenv("LANGUAGE=en_US.utf8");

// Set the text domain as 'messages'
$domain = 'messages';
setlocale(LC_ALL, "en_US.utf8");
bind_textdomain_codeset($domain, 'utf8');
bindtextdomain($domain, PT_LOCALE);
textdomain($domain);

date_default_timezone_set('Europe/Istanbul'); 

/* putenv("LANG=tr_TR.utf8"); */
/* putenv("LANGUAGE=tr_TR.utf8"); */

/* // Set the text domain as 'messages' */

/* $domain = 'messages'; */
/* setlocale(LC_ALL, "tr_TR");// commented for tests */
/* bind_textdomain_codeset($domain, 'utf8'); */
/* bindtextdomain($domain, PT_LOCALE); */
/* textdomain($domain); */

?>