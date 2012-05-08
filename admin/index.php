<?php

define('PANEL_LINKS', 'dashboard');

include("init.php");

$payment =& ON_Payment::getInstance($user, $basket);
$output = $payment->listPayments();

include("theme.php");

?>