<?php

include 'init.php';

$settings = new ON_Settings();
$settings->load();


include PT_INCLUDE . 'ON_Theme.php';
theme_popup();
?>