<?php

// Report all PHP errors except deprecated
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors','On'); 

/* ---[ PROJECT ]------------------------------- */
define('ON_VERSION',         '0.2');
define('ON_PROJECT_NAME',    'Orion');
define('ON_SESSION_NAME',    'Orion');

/* ---[ PATH & URL VARIABLES; prefix PT:path,LC:locations ]------------------------------- */
define('PT_PROJECT', '/orion'); // project path
define('PHP_SELF', basename(filter_var($_SERVER['PHP_SELF'])));
define('PT_SITE', '/home/uzaytek/uzaytek.com/orion/');

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
  define('LC_SITE', 'https://' . $_SERVER['SERVER_NAME'] . PT_PROJECT . '/');
} else {
  define('LC_SITE', 'http://' . $_SERVER['SERVER_NAME']. PT_PROJECT. '/');
}
define('LC_SECURE_SITE',       'http://' . $_SERVER['SERVER_NAME'] . PT_PROJECT . '/');

define('PT_ADMIN',       PT_SITE . 'admin/');
define('LC_ADMIN',       LC_SITE . 'admin/');
define('PT_IMAGE',       PT_SITE . 'uploads/');
define('LC_IMAGE',       LC_SITE . 'uploads/');
define('PT_INCLUDE',     PT_SITE . 'includes/');
define('PT_LOCALE',      PT_SITE . 'locale'); 
define('PT_THEME',       PT_SITE . 'assets/themes/');
define('LC_THEME',       LC_SITE . 'assets/themes/');

//chmod 777
define('PT_UPLOAD',    PT_SITE . 'uploads/');
define('LC_UPLOAD',    LC_SITE . 'uploads/');

/* ---[ DATABASE & EMAIL; prefix DB for database, EM for email  ]------------------------------- */
define('DB_DBASE',    'dbdata');
define('DB_HOST',     'localhost');
define('DB_USER',     'dbuser');//postgres
define('DB_PASSWORD', 'dbpass');
define('EM_ADMIN',    'admin@');

$prefix = 'orion_';
// tables
define('DB_TBL_ADMINS',           $prefix . 'admins');
define('DB_TBL_BANNERS',          $prefix . 'banners');
define('DB_TBL_GLOBALS',          $prefix . 'globals');
define('DB_TBL_NAVS',             $prefix . 'navigations');
define('DB_TBL_CATS',             $prefix . 'categories');
define('DB_TBL_COUNTRIES',        $prefix . 'countries');
define('DB_TBL_FAQS',             $prefix . 'faqs');
define('DB_TBL_FILES',            $prefix . 'files');
define('DB_TBL_PRODUCTS',         $prefix . 'products');
define('DB_TBL_TAGS',             $prefix . 'tags');
define('DB_TBL_PRODUCT_TAGS',     $prefix . 'producttags');
define('DB_TBL_PRODUCT_FAVORS',   $prefix . 'productfavors');
define('DB_TBL_STOCK_ALARMS',     $prefix . 'productstockalarms');
define('DB_TBL_IMAGES',           $prefix . 'productimages');
define('DB_TBL_NEWS',             $prefix . 'news');
define('DB_TBL_NEWSLETTERS',      $prefix . 'newsletters');
define('DB_TBL_PAYMENT_ACCOUNTS', $prefix . 'paymentaccounts');
define('DB_TBL_PAYMENT_GATEWAYS', $prefix . 'paymentgateways');
define('DB_TBL_USERS',            $prefix . 'users');
define('DB_TBL_UBULLETIN',        $prefix . 'usersebulletin');
define('DB_TBL_ORDERS',           $prefix . 'orders');
define('DB_TBL_BASKETS',          $prefix . 'baskets');
define('DB_TBL_LOGINSTRIKES',     $prefix . 'loginstrikes');
define('DB_TBL_LOGS',             $prefix . 'logs');
define('DB_TBL_PAYMENTS',         $prefix . 'payments');
define('DB_TBL_SESSIONS',         $prefix . 'sessions');
define('DB_TBL_STATS',            $prefix . 'stats');
define('DB_TBL_STATTOTALS',       $prefix . 'stattotals');
define('DB_TBL_QUEMAIL',          $prefix . 'quemail');

// payment types
define('ON_PAYPAL_DIRECT_PAYMENT',   1); // direct payment with paypal
define('ON_PAYMENT_GATEWAY',         2);
define('ON_PAYPAL_EXPRESS_CHECKOUT', 3);
define('ON_PAYMENT_ACCOUNT',         4);

// payment status
define('ON_PAYMENT_BEGIN',      1);
define('ON_PAYMENT_WAIT',       2);
define('ON_PAYMENT_RECEIVED',   3);
define('ON_PAYMENT_SENT2BRAND', 4);
define('ON_PAYMENT_CANCEL',     5);

// product status
define('ON_PRODUCT_SUPPLYING', 1);
define('ON_PRODUCT_SENT',      2);
define('ON_PRODUCT_DELIVERED', 3); 

// order status
define('ON_ORDER_BEGIN',        1);
define('ON_ORDER_COMPLETED',    2);
define('ON_ORDER_CANCELLED',    3);

// user actions
define('ON_USER_ACTION_VIEW',   1);
define('ON_ANON_ACTION_VIEW',   2);
define('ON_USER_ACTION_SHARE',  3);
define('ON_ANON_ACTION_SHARE',  4);

// statistics types
define('ON_STAT_PRODUCT',    1);
define('ON_STAT_CATEGORY',   2);

/* ---[ FILE UPLOADS ]------------------------------- */
// max file size as 200KB, for
$_conf['HTML_MAX_FILE_SIZE']['product']  = 204800;
$_conf['HTML_PERMIT_TYPES']['product']   = array('image/jpeg','image/pjeg','image/gif','image/png');
$_conf['HTML_PERMIT_DISPLAY']['product'] = array('jpeg','gif','png');

$_conf['HTML_MAX_FILE_SIZE']['logo']     = 51200;
$_conf['HTML_PERMIT_TYPES']['logo']      = array('image/jpeg','image/pjeg','image/gif','image/png');
$_conf['HTML_PERMIT_DISPLAY']['logo']    = array('jpeg','gif','png');

$_conf['HTML_MAX_FILE_SIZE']['banner']   = 102400;
$_conf['HTML_PERMIT_TYPES']['banner']    = array('image/jpeg','image/pjeg','image/gif','image/png');
$_conf['HTML_PERMIT_DISPLAY']['banner']  = array('jpeg','gif','png');

// 2 mb
$_conf['HTML_MAX_FILE_SIZE']['file']  = 2097152;
$_conf['HTML_PERMIT_TYPES']['file']   = array('application/zip','text/plain','application/msword',
                                              'application/vnd.ms-powerpoint',
                                              'application/vnd.ms-excel','application/pdf');
$_conf['HTML_PERMIT_DISPLAY']['file'] = array('zip','xls','doc','pdf','txt','pps');


// $HTML_permit_types = array('text/plain', 'application/octet-stream','application/vnd.ms-excel');


?>
