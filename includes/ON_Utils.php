<?php

/**
 * Return version info for meta tag
 *
 */
function meta_generator() {
  echo ON_PROJECT_NAME . ' ' . ON_VERSION;
}


/**
 * Make a url link
 *
 * @param string $url The url href
 * @param string $title Url title
 * @return string Formatted Url link
 */
function url($url, $title=null) {
  //$url = str_replace(array('http://','www.',), '', $url); // strip if exists
  return '<a href="'.$url.'">'.
    (($title != null) ? $title : $url).'</a>';
}


/**
 * print menu according to user auth(admin_auth)
 *
 * @return void
 */
function print_menu() {
  if (isset($_SESSION['auth']['admin'])) {
    $admin_type = $_SESSION['auth']['admin']['admintype'];
    $menu = $GLOBALS['_menu_arr'][$admin_type];
    echo "<div id='navcontainer'><ul>";
    $submenu_content = '';
    foreach($menu as $menu_id => $menu_content){
      if ($menu_content[0] != '') {
        echo "<li><a href='".$menu_content[0]."'>";
      }
      if (is_array($menu_content[1])) {
        list($label, $sub_menu_array) = each($menu_content[1]);
        foreach($sub_menu_array as $submenu_id => $submenu_arr){
          $submenu_content .= '[<a href="'.$submenu_arr[0].'">'.$submenu_arr[1].'</a>] ';
        }
      } else {
        $label = $menu_content[1];
      }
      if ($label != '') {
        echo "$label</a></li>";  
      }
    }
    echo "</ul>";
    echo "<p id='utimeinfo'>".$_SESSION['auth']['admin']['adminname']." (".
      $_SESSION['auth']['admin']['adminemail'].") ".date('d/m/Y H:i')."</p>";	
    echo '<br clear="both"><div id="submenu">';
    echo $submenu_content;
    echo '</div>';
    echo '</div><br clear="both">';
  }
}

/**
 * if variable exists in $_REQUEST, return it as query parameter
 *
 * @param string $v variable name
 * @return string if variable exists '?parameter=intval($parameter)', empty string otherwise
 */
function iif($v) {
  return (isset($_REQUEST[$v])) ? "?$v=" . intval($_REQUEST[$v]) : '';
}

/**
 * Print debug backtrace and die
 *
 * @param string $msg Fatal error message
 */
function fatal_error($msg) {
  echo "<pre>Error!: $msg\n";
  $bt = debug_backtrace();
  foreach($bt as $line) {
    $args = var_export($line['args'], true);
    echo "{$line['function']}($args) at {$line['file']}:{$line['line']}\n";
  }
  echo "</pre>";
  die();
}

/**
 * Trucate string according to space (if available)
 *
 * @param string $var The string will be trucated
 * @param integer $len Truncated string length
 */
function truncate($var, $len = 40) {
   if (empty ($var)) { return ''; }
   if (strlen ($var) < $len) { return $var; }
   if (preg_match ("?(.{1,$len})\s.?ms", $var, $match)) { return $match [1] . '...'; }
   else { return substr ($var, 0, $len) . '...'; }
}


/**
 * returns file extension
 *
 * @param string $filename The file name
 * @return string downcase extension of given filename
 */
function getExtension($filename) {
  if (!$filename) {
    throw new Exception('filename required');
  }
  if (!strstr($filename,'.')) {
    throw new Exception('filename extension required "."');
  }
  return strtolower(substr($filename, strrpos($filename, ".")));
}

/**
 * return month array
 *
 * @return array Credit card expire months
 */
function getMonths() {
  $out = array();
  $locale = new ON_Locale();
  for ($i = 1; $i <= 12; $i++) {
    $i = ($i<10) ? '0' . $i : $i; 
    $out[$i] = $locale->month[$i];
  }
  return $out;
}

/**
 * returns credit card expire year values
 *
 * @return array Credit card expire years
 */
function getYears() {
  $out = array();
  for ($i = date("Y"); $i <= date("Y") + 10; $i++) {
    $out[$i] = $i;
  }
  return $out;
}

/**
 * return ip information
 *
 * @return string ip information
 */
function ipCheck() {
  if (getenv('HTTP_CLIENT_IP')) {
    $ip = getenv('HTTP_CLIENT_IP');
  } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
    $ip = getenv('HTTP_X_FORWARDED_FOR');
  } elseif (getenv('HTTP_X_FORWARDED')) {
    $ip = getenv('HTTP_X_FORWARDED');
  } elseif (getenv('HTTP_FORWARDED_FOR')) {
    $ip = getenv('HTTP_FORWARDED_FOR');
  } elseif (getenv('HTTP_FORWARDED')) {
    $ip = getenv('HTTP_FORWARDED');
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }
  if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
    return 'not found';// db field is 15 character length.
  } else {
    return $ip;
  }
}


/**
 * add hide style if $val true
 *
 * @param string $val if evaluated to true return style
 * @return string hide style if $val true, if $val false empty string
 */
function hide($val) {
  $hide_style = 'style="display:none"';
  return ($val) ? $hide_style : '';
}

/**
 * auto load class file if exists
 *
 * @param string $className Class name file available in the include folder
 */
function __autoload($className) {
  if (file_exists(PT_INCLUDE . $className . ".php")) {
    include_once PT_INCLUDE . $className . ".php";
  }
}

/**
 * print admin top menu
 *
 * @param array $arr The reference of array
 * @return string menu html
 */
function _skin_top_menu(&$arr) {
  return '<ul class="adxm menu">'.implode("\n",_skin_menu_items($arr)).'</ul>';
}

/**
 * generate admin top menu items recursively
 *
 * @param array $arr The reference of array
 * @return array items of top menu
 */
function _skin_menu_items(&$arr) {
  $out = array();
  foreach($arr as $iarr) {
    foreach ($iarr as $i=>$amenu) {
      if ($i == 0) {
        $len = count($iarr);
        $out[] = '<li><a href="'.$amenu[0].'">'.$amenu[2].'</a>'.(($len>1) ? '<ul>' : '</li>');    
      } else {
        if(is_array($amenu[0])) {
          $out[] = implode("\n",_skin_menu_items($amenu));
        } else {
          $out[] = '<li><a href="'.$amenu[0].'">'.$amenu[2].'</a></li>';
        }
      }
    }
    $out[] = (($len>1) ? '</ul></li>' : '');
  }
  return $out;
}

/**
 * clear html tags
 *
 * @param string $val The variable will be clear
 * @return string The cleared string
 */
function clearCodes($val) {
  //return $val;
  return strip_tags($val);
}

/**
 * for ajax or simple database connections
 * 
 */
function connect() {
  try {
    $db = new PDO('pgsql:host=' . DB_HOST . ';dbname='.DB_DBASE, DB_USER, DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $res = $db->query("SHOW VARIABLES LIKE 'version'");
    $mysql_version = $res->fetch();
    if (version_compare($mysql_version[1], '4.2.0', '>=')>0) {
      $db->query("set names utf8"); 
      $db->query("set character set utf8"); 
      $db->query("set character_set_results = utf8");
    }
  } catch (PDOException $e) {
    die($e->getMessage());
  }
  return $db;
}

/**
 * calls php filter_var or filter_var_array
 * 
 * Note: if FILTER_SANITIZE_STRING used in filter_ functions
 * rich text editor html tags will be deleted
 * 
 * @param mixed $val a value which will be filtered
 * @return filtered value
 */
function ON_Filter($val) {
  if (is_array($val)) {
    return filter_var_array($val);
  } else {
    return filter_var($val);
  }
}

/**
 * create handlers for products, navigations for seo
 * used in the detail page, navigation links
 * 
 * @param $val string the product name or navigation name
 * @return string url handler
 */
function createHand($val) {

  if (!$val || strlen($val)==0) {
    throw new Exception('create handler needs a string parameter');
  }

  // replace all non-ascii letters and space with `-`
  $val = ereg_replace('[^a-zA-Z0-9]','-', $val);
  // handler max. word must be 4
  $tmp = explode('-', $val);
  if (count($tmp) > 4) {
    $val = implode('-',array_slice($tmp, 0, 4));
  }
  // handler max. length 200
  if (strlen($val) > 200) {
    $val = substr($val, 0, 200);
  }
  assert('!strstr($val,"_")');// _ used in url for seperate product id and product name
  return mb_strtolower($val);
}

/**
 * generates a random string
 *
 * @param integer $iLength string length
 * @param boolean $lowercase if true string returned as lowercase
 * @return string
 */
function genRandStr($iLength = 5, $lowercase = false) {
  // exclude characters like l,o,1,0 for confusion
  $allchars = 'abcdefhknmprstuvwxyzABCDEFHKNMPRSTUVWXYZ23456789'; 
  if ($lowercase) {
    $allchars = strtolower($allchars);
  }
  $string = ''; 
  $len = strlen($allchars)-1;
  mt_srand((double) microtime() * 1000000); 
  for ($i = 0; $i < $iLength; $i++) { 
    $string .= $allchars{mt_rand(0, $len)};
  }
  return $string;
} 

/**
 * format byte values as kilobyte
 *
 * @param integer $byte Byte value
 * @return string kilo byte value
 */
function fmtKB($byte) {
  return intval($byte/1024)."KB";
}

/**
 * start timing
 *
 * @param string $name Timer name
 */
function start_timing($name = 'default') {
  global $start_times;
  $start_times[$name] = explode(' ', microtime());
}

/**
 * stop timing and return calculated difference
 *
 * @param string $name Timer name
 * @param integer timer difference
 */
function stop_timing($name = 'default') {
  global $start_times;
  if (!isset($start_times[$name])) {
    return 0;
  }
  $stop_time = explode(' ', microtime());
  // do the big numbers first so the small ones aren't lost
  $current = $stop_time[1] - $start_times[$name][1];
  $current += $stop_time[0] - $start_times[$name][0];
  return $current;
}

/**
 * if username exists return false i.e username not unique
 *
 * ON_QuickForm rule function
 * 
 * @param string $element_name Rule element name
 * @param string $element_name Rule element value i.e. username value
 * @param array  $args Array of given parameters
 * @param boolean username exists return false i.e username not unique
 */
function isUsernameUnique($element_name, $element_value, $args) {
  $user =& ON_User::getInstance();
  $userid = (isset($args['userid'])) ? (int)$args['userid'] : 0;
  if($user->isUsernameExists($element_value, $userid)) {
    // if exists in another row return false, for form validate
    return false;
  } else {
    return true;
  }
}

/**
 * format price according to locale
 *
 * @param double  $value The price value
 * @param boolean $bsymbol If true currency symbol used
 * @return string Formated price and server locale currency symbol
 */
function fmtPrice($value, $bsymbol=true) {
  $locale = new ON_Locale();
  $m = number_format((double)$value, 2, $locale->decimal_point, $locale->thousands_sep);
  if ($bsymbol == true) {
    if ($locale->p_cs_precedes) {
      return '<span class="price">'.$locale->currency_symbol . $m.'</span>';
    } else {
      return '<span class="price">'.$m . $locale->currency_symbol.'</span>';
    }
  } else {
    return '<span class="price">'.$m.'</span>';
  }
}

/**
 * Format warning messages
 *
 * @param string $sErrorText The error text
 * @return void
 */
function fmtWarn($sText, $class='warn') {
  if ($sText != "") {
    return "<div class='$class'>$sText</div>";
  }
}

/**
 * Format error messages
 *
 * @param string $sErrorText The error text
 * @return void
 */
function fmtError($sErrorText, $class='error') {
  if ($sErrorText != "") {
    return "<div class='$class'>$sErrorText</div>";
  }
}

/**
 * Format success messages
 *
 * @param string $sSuccessText The success text
 * @return void
 */
function fmtSuccess($sSuccessText) {
  if ($sSuccessText != "") {
    return "<div class='success'>$sSuccessText</div>";
  }
}

?>