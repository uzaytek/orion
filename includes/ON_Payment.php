<?php

/**
 * Payment class for payments table
 *
 */ 
class ON_Payment extends ON_Dao
{

  /**
   * User object
   *
   * @var object
   */ 
  private $ob_user;


  /**
   * Basket object
   *
   * @var object
   */ 
  private $ob_basket;

  /**
   * static property to hold singleton instansce
   * 
   * @var boolean
   */
  static $instance = false;


  /**
   * Constructor of payment
   *
   * @param mixed $user The user object if a logged in user
   */ 
  protected function __construct(&$user=null, &$basket=null) {
    parent::__construct(array('table'=> DB_TBL_PAYMENTS, 'pk'=>'paymentid','seq'=>'_nid_seq'),
                        array('paymentid','userid','paymenttype','typeid','typetitle',
                              'paymentkey','paymentstatus',
                              'total','paymentowner','ccardnumber','paymentdate','orderstatus',
                              'isdeleted','ip','paymentnote',
                              'dtcreated','dtmodified','dtdeleted')
			);

    if (is_object($user)) {
      $this->ob_user = $user;
      $this->userid = $user->userid;
    }

    if (is_object($basket)) {
      $this->ob_basket = $basket;
    }

  }

  /**
   * factory method to return singleton instance
   * 
   * @return ON_Payment
   */
  public function getInstance(&$user, &$basket) {
    if(!ON_Payment::$instance) {
      ON_Payment::$instance = new ON_Payment($user, $basket);
    }
    return ON_Payment::$instance;
  }

  /**
   * Insert values to db
   *
   * @param integer $paymentid The database row id 
   * @param integer $paymenttype Payment type constants
   * @param integer $typeid Payment type id (gateway or account id)
   * @param string  $typetitle Payment type title (gateway or account title; only display purpose)
   * @param array   $values Form values
   * @return true if success, exception otherwise
   */  
  public function insert(&$paymentid, $paymenttype, $typeid, $typetitle, &$values) {
    $paymentid = 0;
    $flag = true;
    $total  = $this->ob_basket->getTotal($this->ob_user->userid, $this->getPaymentKey());
    $this->setValues($values, $paymenttype, $typeid, $typetitle, $total);
    $this->dtcreated = $this->getDate('dt');
    $this->paymentkey    = ON_Filter($this->getPaymentKey());
    $this->paymentstatus = ON_PAYMENT_BEGIN;
    $this->orderstatus   = ON_ORDER_BEGIN;
    $this->isdeleted = 0;
    parent::beginTransaction();
    if ($flag) {
      $flag = parent::insert($paymentid);
      if($flag) {$flag = $this->ob_basket->updatePaymentID($paymentid, $this->paymentkey);}
      if($flag) {$flag = $this->ob_user->update($this->userid);}
    }
    if ($flag) {parent::commitTransaction();}
    if ($flag) {
      return true;
    } else {
      throw new Exception(_('Payment Insert Failed'));
    }
  }

  /**
   * Set payment values
   * 
   * @param array   $values The form values filled by  user
   * @param integer $paymenttype Payment type constants
   * @param integer $typeid Payment type id (gateway or account id)
   * @param string  $typetitle Payment type title (gateway or account title; only display purpose)
   * @param float   $total Total payment
   */  
  public function setValues(&$values, $paymenttype, $typeid, $typetitle, $total) {
    $this->paymenttype   = (int)$paymenttype;
    $this->typeid        = (int)$typeid;
    $this->typetitle     = ON_Filter($typetitle);
    $this->total         = (double)$total;
    $this->paymentowner  = isset($values['paymentowner']) ? ON_Filter($values['paymentowner']) : null;
    $this->paymentdate   = isset($values['paymentdate'])
      ? $this->getDate('dt', $values['paymentdate'])
      : $this->getDate('dt');
    $this->ccardnumber   = isset($values['ccardnumber']) ? substr(ON_Filter($values['ccardnumber']), -4) : null;
    $this->ip            = ipCheck();
    $this->paymentnote   = isset($values['paymentnote']) ? ON_Filter($values['paymentnote']) : null;
    $this->ob_user->setValues($values);    
  }

  /**
   * Generate a payment key
   *
   * Return a peyment key, saves generated key in the session also.
   *
   * @return string payment key
   */  
  public function getPaymentKey() {

    $session =& ON_Session::instance();

    if ($session->ifset('paymentkey')) {
      return ON_Filter($session->get('paymentkey'));
    } else {
      try {
        if(!self::$db) $this->connect();
        mt_srand((double) microtime() * 1000000); 
        $paymentkey = date('YmdHis_').mt_rand(0, 100000);
        $session->set('paymentkey', $paymentkey);
        return $paymentkey;	
      } catch (PDOEXCEPTION $e) {
        $this->fatal_error($e->getMessage());
      }
    }
  }

  /**
   * Paginate payment rows
   *
   * Join with basket table
   * 
   * @param object $pager Pager object for pagination
   * @param integer $numrows The row count of the table
   * @param string $where Add to query if exists
   * @return boolean True if success otherwise false
   */  
  public function pager(&$pager, &$numrows, $where=null) {
    $query = 'SELECT a.*'.
      ' FROM ' . $this->reg['table'] . ' a ' .
      ' LEFT JOIN '.DB_TBL_BASKETS.' s ON a.paymentid=s.paymentid ' .
      ' WHERE (a.isdeleted=0 OR a.isdeleted IS NULL) AND s.paymentid > 0 ';

    if ($where) {$query .= $where;}
    $query .= ' GROUP BY s.paymentid ORDER BY a.dtcreated DESC';
    return parent::_pager($pager, $numrows, null, $query);
  }

  /**
   * Paginate payment rows more details
   *
   * Join with basket, product, users table
   * 
   * @param object $pager Pager object for pagination
   * @param integer $numrows The row count of the table
   * @param string $where Add to query if exists
   * @return boolean True if success otherwise false
   */  
  public function detailPager(&$pager, &$numrows, $where=null) {

    $query = 'SELECT a.*,p.productname,s.price,s.basketid,s.productstatus,s.itemcount,u.*'.
      ' FROM ' . $this->reg['table'] . ' a ' .
      ' LEFT JOIN '.DB_TBL_BASKETS.' s ON a.paymentid=s.paymentid ' .
      ' LEFT JOIN '.DB_TBL_PRODUCTS.' p ON s.productid=p.productid ' .
      ' LEFT JOIN '.DB_TBL_USERS.' u ON a.userid=u.userid ' .
      ' WHERE (a.isdeleted=0 OR a.isdeleted IS NULL) ';

    if ($where) {$query .= ' AND '.$where;}
    return parent::_pager($pager, $numrows, null, $query);
  }

  /**
   * Filter row values for display purpose
   *
   * @param mixed $row reference of the database row values
   * @return void
   */  
  public function getfilter(&$row) {
    if (is_object($row)) {
      $a = get_object_vars($row);
      foreach ($a as $k=>$v) {
        $row->{$k} = $this->_getfilter($k, $v);
      }
    } else if(is_array($row)){
      foreach ($row as $k=>$v) {
        $row[$k] = $this->_getfilter($k, $v);
      }
    } else {
      $row = ON_Filter($row);
    }
  }

  /**
   * Filter key values for display purpose
   *
   * @param string the database row key
   * @return string filtered value
   */  
  private function _getfilter($key, $value) {    
    switch($key) {
    case 'phone':
    case 'fax':
      if ($value != '' && ereg("([0-9]{3})-([0-9]{7})",$value, $regs)) {              
        $value = $regs[1].'-'.substr($regs[2],0,3).' '.substr($regs[2],3,2).' '.substr($regs[2],5,2);
      }       
      break;
    case 'paymenttype':
      $value = $this->get_payment_type($value);
      break;
    case 'orderstatus':
      $value = $this->get_order_status($value);
      break;
    case 'paymentstatus':
      $value = $this->get_payment_status($value);
      break;
    case 'productstatus':
      $value = $this->get_product_status($value);
      break;
    case 'dtcreated':
      $value = $this->getDate('ol',$value);
      break;
    case 'paymentdate':
      $value = $this->getDate('o',$value);
      break;

    }
    return ON_Filter($value);
  }

  /**
   * Return payment type string for display purpose
   *
   * @param integer $value the database payment type value
   * @param integer $typeid the database payment typeid value
   * @return string displayed text
   */  
  function get_payment_type($value) {
    switch ($value) {
    case ON_PAYMENT_GATEWAY:
      return _('With Gateway');
      break;
    case ON_PAYPAL_DIRECT_PAYMENT:
      return _('Paypal Direct Payment');
      break;
    case ON_PAYPAL_EXPRESS_CHECKOUT:
      return _('Paypal Express Checkout');
      break;
    case ON_PAYMENT_ACCOUNT:
      return _('With Account');
      break;
    default:
      return _('Undefined');
      break;
    }
  }

  /**
   * Return payment status string for display purpose
   *
   * @param integer $value the database payment status value
   * @return string displayed text
   */  
  function get_payment_status($value) {
    switch ($value) {
    case ON_PAYMENT_CANCEL:
      return _('Payment Cancel');
      break;
    case ON_PAYMENT_RECEIVED:
      return _('Payment Received');
      break;
    case ON_PAYMENT_BEGIN:
    case ON_PAYMENT_WAIT:
    default:    
      return _('Payment Waiting');
      break;
    }
  }

  /**
   * return order status text according to config values
   *
   * @return string Payment status text
   */
  function get_order_status($value) {
    switch ($value) {
    case ON_ORDER_CANCELLED:
      return _('Order Cancelled');
      break;
    case ON_ORDER_COMPLETED:
      return _('Order Completed');
      break;
    case ON_ORDER_BEGIN:
    default:    
      return _('Order Begin');
      break;
    }
  }


  /**
   * Return product status text in the shipping process
   *
   * @param integer $value the database payment status value
   * @return string displayed text
   */  
  function get_product_status($value) {
    switch ($value) {
    case ON_PRODUCT_SUPPLYING:
      return _('In the supply process');
      break;
    case ON_PRODUCT_SENT:
      return _('In the cargo process');
      break;
    case ON_PRODUCT_DELIVERED:
      return _('Cargo delivered');
      break;
    default:
      return _('Undefined');
      break;
    }
  }

  /**
   * Return payment options html
   * 
   * @return string html of payment options
   */  
  function paymentOptions() {
    $output ='<p><strong>'._('Payment Options').'</strong></p><ul>';

    $enc = new ON_Enc();

    $gates  = new ON_PaymentGateway();
    $aGates = $gates->getAll();
    foreach ($aGates as $gate) {
      $id = $enc->encrypt($gate['gateid']);
      $output .= '<li><a href="'.LC_SECURE_SITE.'payment/gateway.php?gateid='.$id.'">'.$gate['title'].'</a></li>';
    }

    $accounts  = new ON_PaymentAccount();
    $aAccounts = $accounts->getAll();
    foreach ($aAccounts as $account) {
      $id = $enc->encrypt($account['accountid']);
      $output .= '<li><a href="'.LC_SECURE_SITE.'payment/account.php?accountid='.$id.'">'.$account['title'].'</a></li>';
    }

    $output .= '</ul>';
    return $output;
  }

  /**
   * Return payment list html
   * 
   * @param booelan $isclosed If isclosed 1 payment completed and archived
   * @return string html of payment list
   */  
  function listPayments($orderstatus=null) {

    $where = ($orderstatus === null)
      ? ' AND (a.orderstatus IN(0,1) OR a.orderstatus IS NULL)'
      : ' AND a.orderstatus='.(int)$orderstatus;  

    $res = $this->pager($pager, $numrows, $where);
    $output = '';
    if ($res) {
      $_options = array(
                        'payment_details.php?'   => _('Details'),
                        );
      if (defined('IN_ADMIN_PANEL')) {
        $_store_options = array('list_payments.php?go=payment_received'   => _('Payment Received'),
                                'list_payments.php?go=order_completed'    => _('Order Completed'),
                                'list_payments.php?go=order_cancelled'    => _('Order Cancelled'),
                                );  
      }

      $inline_form = new ON_SelectForm(array_merge($_store_options, $_options));
      $t = new ON_Table($inline_form);
      $t->title(array(_('Order Number'), _('Payment Type/Status'), _('Order Status'), _('Date/Time')));  
      $e = new ON_Enc();
    
      while($row = $res->fetch()) {
        $this->getfilter($row);
        $t->tr(array($row['paymentkey'], $row['typetitle'] .'<br />'. $row['paymentstatus'],
                     $row['orderstatus'],  $row['dtcreated']), $e->encrypt($row['paymentid']));
      
      }
      $product_tbl = $t->addRow($pager->display(), $t->colCount());
    
      $output = $t->display();
    }
    return $output;
  }

}


?>