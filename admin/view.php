<?php

include 'init.php';

$type = ON_Filter($_REQUEST['t']);

switch ($type) {
case 'logo':
  $fileName = ON_Filter($_REQUEST['f']);
  if(file_exists(PT_UPLOAD.$fileName)) {
    $output = '<p align="center"><img src="' . LC_UPLOAD . $fileName . '" /></p>';
  } else {
    $output = _('Logo file not found or permission denied from filesytem');    
  }
  break;
}


include 'theme_popup.php';

?>
