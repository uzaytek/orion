<?php

include 'init.php';

$stats = new ON_Stat();
$stattotal = new ON_StatTotal();

$stattotal->stattype = $stats->stattype = (int)$_REQUEST['type'];

// selected month, 0=all rows, i.e. total statistics
$selected = (isset($_POST['m']) && $_POST['m'] < 13) ? intval($_POST['m']) : date('m');
$aYearSelect = $stattotal->getDistinctYears();
$year = (isset($_POST['year'])) ? (int)$_POST['year'] : 0;

if ($year > 0) { // a year selected
  $res = $stattotal->getTotalStats($selected, $year);
} else { // show this or selected month
  $res = $stats->getUserActionStats($selected);
}

$output = '';

$form = new ON_QuickForm('monthselect','post','');
$form->setDefaults(array('m'=>$selected, 'stattype'=>$stats->stattype, 'year'=>$year));

$form->addELement('hidden', 'type', $stats->stattype);

$form->addElement('select', 'm', _('Month'), getMonths());
$form->setInlineTemplate('m');

if (count($aYearSelect) > 0) {
  $form->addElement('select', 'year', _('Year'), $aYearSelect);
  $form->setInlineTemplate('year');
}

$form->addElement('submit', 'sb', _('Go'), 'class="mi"');
$form->setInlineTemplate('sb');

$output .= $form->toHtml();

// records
if ($res->rowCount() > 0) {
  $t = new ON_Table();
  if ($stats->stattype == ON_STAT_PRODUCT) {
    $t->addCol(array(_('Product Name')));
  } else {
    $t->addCol(array(_('Category Title')));
  }
  $t->addCol(array(_('Anon. View')),'title="'._('Anonymous View').'"');
  $t->addCol(array(_('User View')),'title="'._('User View').'"');
  $t->addCol(array(_('Anon. Share')),'title="'._('Anonymous Share').'"');
  $t->addCol(array(_('User Share')),'title="'._('User Share').'"');

  while($row = $res->fetch()) {
    $a = array($row['ai'], $row['ui'], $row['at'], $row['ut']);	   
    if ($stats->stattype == ON_STAT_PRODUCT) {
      array_unshift($a, $row['productname']);
    }
    if ($stats->stattype == ON_STAT_CATEGORY) {
      array_unshift($a, $row['cattitle']);
    }

    $t->tr($a);
  }
  $output .= $t->display();  
} else {
  $output .= fmtError(_('Statistic record not found for the selected month'));
}

include 'theme.php';

?>