<?php

include 'init.php';

$payment =& ON_Payment::getInstance($user, $basket);
$enc = new ON_Enc();

$paymentid = (isset($_REQUEST['id'])) ? $_REQUEST['id'] : 0;

if ($paymentid) {
  $encpaymentid = ON_Filter($paymentid);
  $paymentid = $enc->decrypt($encpaymentid);
}

if (isset($_POST['go']) && isset($_POST['basketid'])) {
  $basket = new ON_Basket();  
  $basketid = $enc->decrypt(ON_Filter($_POST['basketid']));
   
  if (strstr($_POST['go'], 'product_supplying')) {
    $result = $basket->isupdate('productstatus='.ON_PRODUCT_SUPPLYING.', dtmodified=NOW()',$basketid);
    if ($result) {
      ON_Say::add(fmtSuccess(_('Product marked as in the supply')));
    }
  }
  
  if (strstr($_POST['go'], 'product_sent')) {
    $result = $basket->isupdate('productstatus='.ON_PRODUCT_SENT.', dtmodified=NOW()',$basketid);
    if ($result) {
      ON_Say::add(fmtSuccess(_('Product marked as in the cargo')));
    }
  }
  
}// end if set post[go']

$res = false;
if ($paymentid) {
  $where = ' a.paymentid='.(int)$paymentid;
  $res = $payment->detailPager($pager, $numrows, $where);
}
  
if ($res) {
  $_options = array('payment_details.php?id=' . $encpaymentid . '#product_supplying' => _('In Supply'),
                    'payment_details.php?id=' . $encpaymentid . '#product_sent' => _('In Cargo'),
		    );
  $output = '';
  
  $inline_form =& new ON_SelectForm($_options, 'basketid');
  $t = new ON_Table($inline_form);
  $t->title(array(_('Product'), _('Price'), _('Count'), _('Product Status')));  
  $e = new ON_Enc();
  $beginTable = true;
  while($row = $res->fetch()) {
    $payment->getfilter($row);
    if (true == $beginTable) {

      $global = new ON_Global;
      list($aCountry,) = $global->getAllCountries($row['billcountry']);
      $sBillingCountry = (isset($aCountry[$row['billcountry']])) ? $aCountry[$row['billcountry']] : '';
      // beginning of page
      $output = '';
      $innertable = new ON_Table();
      $innertable->title(array(_('Order Details'),''));
      $innertable->tr(array(_('Order Number'),$row['paymentkey']));
      $innertable->tr(array(_('Total'), fmtPrice($row['total'])));     
      $innertable->tr(array(_('Payment Type'), $row['typetitle']));

      if ($row['ccardnumber'] != '') {
        $innertable->tr(array(_('Card Last 4 Digit'), $row['ccardnumber']));
      }

      $innertable->tr(array(_('Date/Time'), $row['dtcreated']));
      $innertable->tr(array(_('Payment Owner Name'), $row['paymentowner']));
      $innertable->tr(array(_('Payment Sent Date'), $row['paymentdate']));
      $innertable->tr(array(_('Payment Status'), $row['paymentstatus']));
      $innertable->tr(array(_('IP'), $row['ip']));
      $innertable->tr(array(_('Billing Name'), $row['billname']));
      $innertable->tr(array(_('Billing Address'), $row['billaddress']));
      $innertable->tr(array(_('Post Code'), $row['billpostcode']));
      $innertable->tr(array(_('City'), $row['billcity']));
      $innertable->tr(array(_('Billing Country'), $sBillingCountry));
      $innertable->tr(array(_('Telephone'), $row['billphone']));
      $innertable->tr(array(_('Note'), nl2br($row['paymentnote'])));
      $output .= $innertable->display();
    }
    $beginTable = false;
    $t->tr(array($row['productname'],
                 fmtPrice($row['price']), $row['itemcount'],
                 '<span class="mark">'.
                 (!empty($row['productstatus']) ? $row['productstatus'] : _('Not defined')).'</span>'),
	   $e->encrypt($row['basketid']));
  }
  $output .= $t->display();
} else {
  $output = fmtError(_('Order record not found'));
}

//$show['rightcolumn'] = false;
include 'theme.php';

?>