<?php

include 'init.php';

$settings = new ON_Settings();
$settings->load();

$userid = $session->get('userid');
if ($userid > 0) {
  $output = fmtSuccess(sprintf(_('You are already logged in, please use <a href="%s">profile page</a>'),'profile.php'));  
} else {
  $_conf['displayPage'] = 'user.register';
}

include PT_INCLUDE . 'ON_Theme.php';

theme($output);
?>