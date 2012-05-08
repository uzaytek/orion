<?php

include 'init.php';

$settings = new ON_Settings();
$settings->load();

$_conf['displayPage'] = (isset($_REQUEST['t'])) ? ON_Filter($_REQUEST['t']) : 'index';


include PT_INCLUDE . 'ON_Theme.php';
theme();
?>