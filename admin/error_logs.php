<?php
include 'init.php';

$log  = new ON_Log();
$log->setOrder('dtcreated DESC');
$res = $log->pager($pager, $numrows);

if ($res) {

  $t = new ON_Table($inline_form);
  $t->title(array(_('Log Value')));

  while($row = $res->fetch()) {
    $t->tr(array($row['logvalue']));
  }
  $t->addRow($pager->display()._('Total Log Count:').$numrows);
  $output = $t->display();
} else {
  $output = fmtError(_('Log records not found'));
}

include 'theme.php';

?>