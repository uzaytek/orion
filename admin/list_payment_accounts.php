<?php
include 'init.php';

$account  = new ON_PaymentAccount();

// delete
if (isset($_POST['go'])) {
  if (strstr($_POST['go'], 'delete_account') && isset($_POST['id'])) {
    $enc = new ON_Enc();
    $accountid = (int)$enc->decrypt($_POST['id']);
    $bupdate = $account->load($accountid);
    if($bupdate) {        
      $result = $account->isupdate('isdeleted=1', $accountid);
      if ($result) {
        ON_Say::add(fmtSuccess(_('Selected payment account deleted successfully')));
      }
    }
  }// is deleted

}

$skin_sub_menu = '<a href="payment_account.php">New Payment Account</a>';
$output = '';

$account->setOrder('title');
$res = $account->pager($pager, $numrows);

if ($res) {

  $inline_form = new ON_SelectForm(array('payment_account.php'                      =>_('Modify'), 
                                         'list_payment_accounts.php#delete_account' =>_('Delete')));
  $t = new ON_Table($inline_form);
  $t->title(array(_('Title'), _('Account')));
  $e = new ON_Enc();

  while($row = $res->fetch()) {
    $t->tr(array($row['title'], $row['detail']),
	   $e->encrypt($row['accountid']));
  }
  $t->addRow($pager->display()._('Total Payment Account Count:').$numrows, $t->colCount());
  $output .= $t->display();
} else {
  $output .= fmtError(_('Payment account record(s) not found'));
}

include 'theme.php';

?>