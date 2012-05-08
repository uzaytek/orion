<?php

/**
 * Basket table dao & functions
 * 
 */  
class ON_Basket extends ON_Dao
{


  /**
   * Construct banner
   * 
   */  
  public function __construct() {
    parent::__construct(array('table'=> DB_TBL_BASKETS, 'pk'=>'basketid', 'seq'=>'basket_nid_seq'),
                        array('basketid','productid','userid','paymentkey','price','itemcount',
                              'dtcreated','dtmodified')
			);
  }

  /**
   * Load basket
   * 
   * @param object $stmt Pear Statement object
   * @param integer $userid User id
   * @param string $paymentkey Payment Key for trace product & process in the basket
   * @return boolean true if load success false otherwise
   */  
  public function load(&$stmt, $userid, $paymentkey) {
    try {
      if(!self::$db) $this->connect();
      $stmt = self::$db->query('SELECT p.productname, b.* FROM '.
                               DB_TBL_PRODUCTS . ' AS p, ' . DB_TBL_BASKETS . ' AS b '.
                               ' WHERE p.productid=b.productid AND b.userid=' . (int)$userid .
                               ' AND b.paymentkey = ' . $this->quote(ON_Filter($paymentkey)));
      if ($stmt->rowCount()) {
        return true;
      } else {
        return false;
      }
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }

  /**
   * Insert product to basket
   *
   * If product were added already return true so no insert will be made to table
   * 
   * @param integer $basketid The database row id which will be inserted
   * @return boolean true if success
   */  
  public function insert(&$basketid) {
    try {	
      if(!self::$db) $this->connect();
      $where = ' WHERE productid='.$this->productid.' AND userid='.$this->userid.
        ' AND paymentkey=' . $this->quote(ON_Filter($this->paymentkey));
      $st = self::$db->query('SELECT basketid FROM '. DB_TBL_BASKETS . $where);
      $basketid = $st->fetchColumn();
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    if (0 == (int)$basketid) {
      $this->itemcount = 1;
      $this->dtcreated = $this->getDate('il');
      return parent::insert($basketid);
    }
    if ($basketid > 0) {
      return true;
    }    
  }

  /**
   * Delete product from basket
   * 
   * @param integer $basketid The database row id which will be deleted
   * @return integer number of rows were affected
   */  
  public function delete($basketid) {
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->exec('DELETE FROM '.DB_TBL_BASKETS.' WHERE basketid='.(int)$basketid);
    } catch (PDOException $e) {
      try { self::$db->rollBack(); } catch (PDOException $e2) {};
      $this->fatal_error($e->getMessage());
    }
    return $result;
  }

  /**
   * Calculate total price for basket according to $paymentkey
   * 
   * @param integer $userid Basket userid, it is shortcut to user
   * @param string $paymentkey The payment key
   * @return mix double as total price value if not found in the basket return false
   */  
  public function getTotal($userid, $paymentkey) {
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query('SELECT SUM(p.price * b.itemcount) FROM '.
                                 DB_TBL_PRODUCTS . ' AS p, ' . DB_TBL_BASKETS . ' AS b '.
                                 ' WHERE p.productid=b.productid AND b.userid=' . (int)$userid .
                                 ' AND b.paymentkey = ' . $this->quote(ON_Filter($paymentkey)).
                                 ' GROUP BY b.paymentkey');
      if ($result) {
        return $result->fetchColumn(); 
      } else {
        return false;
      }
    } catch (PDOException $e) {
      try { self::$db->rollBack(); } catch (PDOException $e2) {};
      $this->fatal_error($e->getMessage());
    }   
  }

  /**
   * Update payment id according to $paymentkey
   * 
   * @param integer $paymentid Payment table id
   * @param string $paymentkey The payment key
   * @return integer the number of rows that were modified if no rows were affected return 0
   */  
  public function updatePaymentID($paymentid, $paymentkey) {
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->exec('UPDATE ' . $this->reg['table'] . ' SET paymentid=' .(int)$paymentid .
                                ' WHERE paymentkey='. $this->quote($paymentkey));
    } catch (PDOException $e) {
      try { self::$db->rollBack(); } catch (PDOException $e2) {};
      $this->fatal_error($e->getMessage());
    }
    return $result;        
  }

  /**
   * Update basket product count values according to basketid=>productcount
   * 
   * @param array $values The item count values
   * @return boolean true if success
   */  
  public function updateItemCounts($values) {
    try {
      if(!self::$db) $this->connect();
      if (is_array($values)) {
        $enc = new ON_Enc();
        foreach($values as $k=>$v) {
          $basketid = (int)$enc->decrypt($k);
          $result = self::$db->exec('UPDATE ' .  $this->reg['table'] .
                                    ' SET itemcount = '.intval($v).'  WHERE basketid='.$basketid);
        }
        return true;
      }
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    return false;
  }

  /**
   * Display basket 
   *
   * @param integer $userid User id
   * @param string $paymentkey The payment key
   * @return string basket html
   */  
  public function display($userid, $paymentkey) {
    if ($this->load($res, $userid, $paymentkey)) {
      $t = new ON_Table();
      $t->title(array(_('Product Name'), _('Price'), _('Count'), _('Sub Total')));
      $total = 0;
      while($row = $res->fetch()) {
        $subtotal = $row['price'] * $row['itemcount'];
        $total += $subtotal;	
        $t->tr(array($row['productname'], fmtPrice($row['price']), $row['itemcount'], fmtPrice($subtotal)));
      } 
      $t->addRow(array('&nbsp;','&nbsp;','&nbsp;',_('Total:&nbsp;') . fmtPrice($total)));
      $output = $t->display();
      return $output;
    }
  }

}

?>
