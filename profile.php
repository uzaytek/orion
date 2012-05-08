<?php

include 'init.php';

$settings = new ON_Settings();
$settings->load();

$userid = $session->get('userid');
if ($userid > 0) {
  $_conf['displayPage'] = 'user.update';
} else {
  $output = fmtError(_('Login failed, please relogin with your username/password'));
}

include PT_INCLUDE . 'ON_Theme.php';
theme($output);
?>