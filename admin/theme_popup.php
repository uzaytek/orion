<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo _('Admin Area'); ?></title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/me.css" />
<style type="text/css">
body {
   font:.7em Verdana, Arial;
   background:none;
}
</style>
</head>
<body>
<div style="width:500px">

<?php
echo ON_Say::get();

if (isset($output)) {
    echo $output;
}

?>
</div>
</body>
</html>