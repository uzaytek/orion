<?php
include 'init.php';

$gate  = new ON_PaymentGateway();

// delete
if (isset($_POST['go'])) {
  if (strstr($_POST['go'], 'delete_gate') && isset($_POST['id'])) {
    $enc = new ON_Enc();
    $gateid = (int)$enc->decrypt($_POST['id']);
    $bupdate = $gate->load($gateid);
    if($bupdate) {        
      $result = $gate->isupdate('isdeleted=1', $gateid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Selected payment gateway deleted successfully')));
      }
    }
  }// is deleted

  if (strstr($_POST['go'], 'change_active') && isset($_POST['id'])) {
    $enc = new ON_Enc();
    $gateid = (int)$enc->decrypt($_POST['id']);
    $bupdate = $gate->load($gateid);
    if($bupdate) { 
      $active = ($gate->isactive == 1) ? 0 : 1;       
      $result = $gate->isupdate('isactive='.$active, $gateid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Selected payment gateway active/passive changed successfully')));
      }
    }
  }// change active

  if (strstr($_POST['go'], 'change_mode') && isset($_POST['id'])) {
    $enc = new ON_Enc();
    $gateid = (int)$enc->decrypt($_POST['id']);
    $bupdate = $gate->load($gateid);
    if($bupdate) { 
      $mode = ($gate->istestmode == 1) ? 0 : 1;       
      $result = $gate->isupdate('istestmode='.$mode, $gateid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Selected payment gateway mode changed successfully')));
      }
    }
  }// change mode


}

$skin_sub_menu = '<a href="payment_gateway.php">New Payment Gateway</a>';
$output = '';

$gate->setOrder('title');
$res = $gate->pager($pager, $numrows);

if ($res) {

  $inline_form = new ON_SelectForm(array('payment_gateway.php'                      =>_('Modify'), 
                                         'list_payment_gateways.php#change_active'  =>_('Change Active/Passive'),
                                         'list_payment_gateways.php#change_mode'    =>_('Change Real/Test Mode'),
                                         'list_payment_gateways.php#delete_gate'    =>_('Delete')));
  $t = new ON_Table($inline_form);
  $t->title(array(_('Title'), _('Username'), _('Client ID'), _('Active'), _('Mode')));
  $e = new ON_Enc();

  while($row = $res->fetch()) {
    $active = ($row['isactive']==1) ? _('Active') : _('Passive');
    $mode = ($row['istestmode']==1) ? _('Test Mode') : _('Real Mode');
    $t->tr(array($row['title'], $row['username'], $row['clientid'], $active, $mode ),
	   $e->encrypt($row['gateid']));
  }
  $t->addRow($pager->display()._('Total Payment Gateway Count:').$numrows, $t->colCount());
  $output .= $t->display();
} else {
  $output .= fmtError(_('Payment gateway record(s) not found'));
}

include 'theme.php';

?>