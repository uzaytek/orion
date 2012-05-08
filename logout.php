<?php

include 'init.php';

$settings = new ON_Settings();
$settings->load();

$output = '';

if ($session->ifset('userid')) {
  $session->clear('userid');
}

if (!$session->ifset('userid')) {
  $output .= fmtSuccess(_('Your session closed successfuly'));
} else {
  $output .= fmtError(_('Session close failed'));
}


include PT_INCLUDE . 'ON_Theme.php';
theme($output);



?>



