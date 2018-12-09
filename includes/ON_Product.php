<?php

/**
 * Product dao object
 */
class ON_Product extends ON_Dao
{

  /**
   * Quickform object
   *
   * @var object
   */
  public $form;

  /**
   * if submitted form values valid for insert/update process
   *
   * @var boolean
   */
  public $isFormValid;

  /**
   * ON_Tag object, tags of product
   *
   * @var object
   */
  private $ob_tags;

  /**
   * xfa array, list/search pages select options
   *
   * @var array
   */
  public $modifyOptions;

  /**
   * Construct 
   */
  public function __construct()
  {
    parent::__construct(array('table'=> DB_TBL_PRODUCTS, 'pk'=>'productid', 'seq'=> ''),
                        array('productid','catid','productname','productdetail','stockcount','oldstockcount',
                              'urlhandler','price',
                              'isanewone','isapromote','isaheadline','isabestseller','isdeleted',
                              'campaignprice','dtcampaignstart','dtcampaignstop',
                              'dtcreated','dtmodified','dtdeleted'));

    $this->setWhere('(isdeleted=0 OR isdeleted IS NULL)', '');
    $this->isFormValid = false;

    $this->modifyOptions = array('product.php'=>_('Modify'),
                                 'product_image.php'=>_('Add Image'),
                                 'list_products.php#delete_product'=>_('Delete'));


  }

  /**
   * Paginate product rows for search
   *
   * Join with images table
   * 
   * @param object  $pager Pager object for pagination
   * @param integer $numrows The row count of the table
   * @param array   $pagerOptions The pager object options
   * @param string  $where Add to query if exists
   * @return boolean True if success otherwise false
   */  
  public function searchPager(&$pager, &$numrows, $pagerOptions=null, $where=null) {
    $query = 'SELECT a.*, i.hostfilename, i.fileextension' .
      ' FROM ' . $this->reg['table'] . ' a ' .
      ' LEFT JOIN ' . DB_TBL_IMAGES.' i ON a.productid=i.productid AND i.isdefault=1' .
      ' WHERE (a.isdeleted=0 OR a.isdeleted IS NULL) ';
    if ($where) {$query .= ' AND '.$where;}
    $query .= $this->orderby();
    return parent::_pager($pager, $numrows, $pagerOptions, $query);
  }

  /**
   * Paginate product rows for lists
   *
   * Join with images table
   * 
   * @param object  $pager Pager object for pagination
   * @param integer $numrows The row count of the table
   * @param string  $where Add to query if exists
   * @return boolean True if success otherwise false
   */  
  public function pager(&$pager, &$numrows, $pagerOptions=null, $where=null) {

    $user =& ON_User::getInstance();

    $join_extra = $field_extra = '';

    $session =& ON_Session::instance();
    $userid = $session->get('userid');

    if (false && $userid) {
      $join_extra  = ' LEFT JOIN '.DB_TBL_PRODUCT_FAVORS.' f ON a.productid=f.productid AND f.userid='.(int)$userid;
      $join_extra .= ' LEFT JOIN '.DB_TBL_STOCK_ALARMS.' sa ON a.productid=sa.productid AND sa.userid='.(int)$userid;
      $field_extra = ',f.productid AS favor, sa.productid AS stockalarm';
    }

    $query = 'SELECT a.*, i.hostfilename, i.fileextension'.
      $field_extra.
      ' FROM ' . $this->reg['table'] . ' a ' . $join_extra .
      ' LEFT JOIN '.DB_TBL_IMAGES.' i ON a.productid=i.productid AND i.isdefault=1' .
      ' WHERE (a.isdeleted=0 OR a.isdeleted IS NULL) ';

    if ($where) {$query .= $where;}
    $query .= $this->orderby();

    return parent::_pager($pager, $numrows, $pagerOptions, $query);
  }

  /**
   * Paginate product rows for favorites
   * TODO: why dont use normal pager, is this method required really?
   *
   * Join with images table
   * 
   * @param object  $pager Pager object for pagination
   * @param integer $numrows The row count of the table
   * @param string  $where Add to query if exists
   * @return boolean True if success otherwise false
   */  
  public function favorPager(&$pager, &$numrows, $where=null) {
    $user =& ON_User::getInstance();
    $userid = $user->getLoginID();

    if ($userid) {
      $query = 'SELECT a.*, i.hostfilename, i.fileextension,f.productid AS favor'.
	' FROM ' . $this->reg['table'] . ' a '.
	' INNER JOIN '.DB_TBL_PRODUCT_FAVORS.' f ON a.productid=f.productid AND f.userid='.(int)$userid.
	' LEFT JOIN '.DB_TBL_IMAGES.' i ON a.productid=i.productid AND i.isdefault=1' .
	' WHERE (a.isdeleted=0 OR a.isdeleted IS NULL) ';

      if ($where) {$query .= ' AND '.$where;}
      return parent::pager($pager, $numrows, $query);
    }
  }

  /**
   * Paginate product rows for stock alarms
   * TODO: why dont use normal pager, is this method required really?
   *
   * Join with images table
   * 
   * @param object  $pager Pager object for pagination
   * @param integer $numrows The row count of the table
   * @param string  $where Add to query if exists
   * @return boolean True if success otherwise false
   */  
  public function stockAlarmPager(&$pager, &$numrows, $where=null) {
    $user =& ON_User::getInstance();
    $userid = $user->getLoginID();    

    if ($userid) {
      $query = 'SELECT a.*, i.hostfilename, i.fileextension,sa.productid AS stockalarm'.
	' FROM ' . $this->reg['table'] . ' a '.
	' INNER JOIN '.DB_TBL_STOCK_ALARMS.' sa ON a.productid=sa.productid AND sa.userid='.(int)$userid.
	' LEFT JOIN '.DB_TBL_IMAGES.' i ON a.productid=i.productid AND i.isdefault=1' .
	' WHERE (a.isdeleted=0 OR a.isdeleted IS NULL) ';

      if ($where) {$query .= ' AND '.$where;}
      return parent::pager($pager, $numrows, $query);
    }
  }

  /**
   * Update values
   *
   * @param integer $productid The database row id 
   */  
  public function update($productid) {

    if ($this->isFormValid !== true) {
      return false;
    }

    $this->dtmodified = $this->getDate('dt');

    parent::beginTransaction();
    $flag = parent::update($productid);    
    // tags are multi row elements so we dont update, delete old records and insert again
    if ($flag) {$flag = $this->ob_tags->insertProductTags($productid);}
    if ($flag) {parent::commitTransaction();}
    if ($flag) {
      $this->fillStockAlarms();
      return true;
    } else {
      return false;
    }
  }

  /**
   * Insert values
   *
   * @param integer $productid The database row id 
   */  
  public function insert(&$productid) {

    if ($this->isFormValid !== true) {
      return false;
    }

    $productid = 0;
    $this->dtcreated = $this->getDate('dt');
    parent::beginTransaction();
    $flag = parent::insert($productid);    
    if ($flag) {$flag = $this->ob_tags->insertProductTags($productid);}
    if ($flag) {parent::commitTransaction();}
    if ($flag) {
      return true;
    } else {
      return false;
    }    
  }

  /**
   * Delete product row
   *
   * @param integer $productid The database row id 
   */  
  public function delete($productid) {
    $ret = parent::delete($productid);
    return $ret;
  }

  /**
   * Return default values
   *
   * @return array default product values
   */  
  public function defaults() {    
    
    if($this->price) {
      $this->price = number_format($this->price, 2, '.', '');
    }

    if($this->campaignprice) {
      $this->campaignprice = number_format($this->campaignprice, 2, '.', '');
    }


    $tag_arr = array();
    if ($this->productid > 0) {
      $tags = new ON_Tag();
      $tag_arr = array('tags'=> $tags->loadProductTags($this->productid));
    }

    $ispages = array();
    if ($this->isanewone > 0) {
      $ispages['ispages']['isanewone'] = 1;
    }

    if ($this->isapromote > 0) {
      $ispages['ispages']['isapromote'] = 1;
    }

    if ($this->isaheadline > 0) {
      $ispages['ispages']['isaheadline'] = 1;
    }

    if ($this->isabestseller > 0) {
      $ispages['ispages']['isabestseller'] = 1;
    }


    if ($this->dtcampaignstart) {
      $this->dtcampaignstart = $this->getDate('o', $this->dtcampaignstart);
    }

    if ($this->dtcampaignstop) {
      $this->dtcampaignstop = $this->getDate('o', $this->dtcampaignstop);
    }


    return array_merge(parent::defaults(), $tag_arr, $ispages);
  }

  /**
   * Set product values
   * 
   * @param array $values The form values
   */  
  public function setValues(&$values) {
    $this->catid         = (int)$values['catid'];
    $this->productname   = ON_Filter($values['productname']);
    $this->productdetail = ON_Filter($values['productdetail']);
    $this->oldstockcount = (int)$this->stockcount;
    $this->stockcount    = 0;//(int)$values['stockcount'];
    $this->price         = number_format(ON_Filter($values['price']), 2, '.', '');
    // new product
    if(isset($values['ispages']['isanewone'])) $this->isanewone   = (int)$values['ispages']['isanewone'];
    // promote page
    if(isset($values['ispages']['isapromote'])) $this->isapromote = (int)$values['ispages']['isapromote'];
    // headline
    if(isset($values['ispages']['isaheadline'])) $this->isaheadline = (int)$values['ispages']['isaheadline'];
    // bestseller
    if(isset($values['ispages']['isabestseller'])) $this->isabestseller = (int)$values['ispages']['isabestseller'];

    if(isset($values['campaignprice'])) $this->campaignprice = number_format(ON_Filter($values['campaignprice']), 2, '.', '');
    if(isset($values['dtcampaignstart'])) $this->dtcampaignstart = $this->getDate('dt', $values['dtcampaignstart']);
    if(isset($values['dtcampaignstop'])) $this->dtcampaignstop = $this->getDate('dt', $values['dtcampaignstop']);

    // insert/update tags to db
    $this->ob_tags = new ON_Tag($values['tags']);
  }

  /**
   * Register quickform object
   * 
   * @param string $formName The html form name 
   */
  public function registerForm($formName) {
    //$formName, $method='post', $action='', $target='', $attr=null,
    $this->form     = new ON_QuickForm($formName, 'post');
  }

  /**
   * Call quickform form validate
   * if form valid true call setValues
   *
   * @return boolean isFormValid
   */  
  public function validate() {
    $this->isFormValid = $this->form->validate();
    if ($this->isFormValid) {
      $values =& $this->form->exportValues();
      // get old stock count before set new values for stock alarms
      $this->setValues($values);      
    }
    return $this->isFormValid;
  }

  /**
   * Register form elements
   *
   */  
  public function fillRegisterForm() {

    // set form defaults, $defaults array will be used after update/insert process
    $defaults = $this->defaults();
    $this->form->setDefaults($defaults);

    // add hidden element to session for controller purpose
    if ($this->productid > 0) {
      $enc = new ON_Enc();
      $this->form->addElement('hidden', 'id', $enc->encrypt($this->productid));
    }

    if (isset($_REQUEST['currentpage'])) {
      $this->form->addElement('hidden', 'currentpage', intval($_REQUEST['currentpage'])); 
    }

    // add elements
    $this->form->addElement('text', 'productname', _('Product Name')); 
    $this->form->addRule('productname',  _('Product name required'), 'required', null, 'client');
    
    $cat = new ON_Cat();
    $this->form->addElement('select', 'catid', _('Product Category'), $cat->getCatTitles());
    $this->form->addRule('catid',_('Product Category required'), 'required', null, 'client');
    
    $this->form->addElement('text', 'price', _('Price'), array('class'=>'money'));
    $locale = new ON_Locale();
    $this->form->setAddNoteTemplate('price', $locale->currency_symbol);

    $this->form->addRule('price', _('Price required!'), 'required', null, 'client');
    $this->form->addRule('price', _('Price must be numeric'), 'numeric', null, 'client');

    $this->form->addElement('text', 'tags', _('Tags'), array('id'=>'ac_tags')); // id important for autocomplete

    $t = new ON_Tag();
    $arr_tags = array();
    $js_tags = 'new Array()';
    if ($t->getTags($arr_tags)) {
      $arr_tagnames = array();
      foreach($arr_tags as $tagid=>$row) {
        $arr_tagnames[] = htmlspecialchars($row['tagname'], ENT_NOQUOTES);
      }
      $tag_string = implode(",", array_map(create_function('$arg','return "\'".$arg."\'";'), $arr_tagnames));
      $js_tags = 'new Array('.$tag_string.')';
    }
    $this->form->setAutoComplete('tags', 'ac_tags', $js_tags);
    
    $_check[] = $this->form->createElement('checkbox', 'isanewone', null, _('New Products'), array('class'=>'borderless'));
    $_check[] = $this->form->createElement('checkbox', 'isapromote', null, _('Popular Products'), array('class'=>'borderless'));
    $_check[] = $this->form->createElement('checkbox', 'isaheadline', null, _('Show in the Headlines box'), 
                                           array('class'=>'borderless'));
    $_check[] = $this->form->createElement('checkbox', 'isabestseller', null, _('Show in the Bestseller box'), 
                                           array('class'=>'borderless'));

    $this->form->addGroup($_check, 'ispages', _('Show in the Page/Area:'), '<br />');

    
    $this->form->addElement('textarea', 'productdetail', _('Description'), 'id="productdetail"');
    $this->form->setRichTextTemplate('productdetail');

    $this->form->addElement('header', 'cc', _('Campaign'));

    $this->form->addElement('text', 'campaignprice', _('Campaign Price'), array('class'=>'money'));
    $locale = new ON_Locale();
    $this->form->setAddNoteTemplate('campaignprice', $locale->currency_symbol);


    $this->form->addElement('text', 'dtcampaignstart', _('Campaign Start Date'),'class="mi" id="dtcampaignstart"');
    $this->form->setDateTemplate('dtcampaignstart');

    $this->form->addElement('text', 'dtcampaignstop', _('Campaign Stop Date'),'class="mi" id="dtcampaignstop"');
    $this->form->setDateTemplate('dtcampaignstop');

    $this->form->addElement('submit', 'sb', _('Save'), 'class="mi"');
  }

  /**
   * Search form elements
   *
   */  
  public function searchForm(&$form) {
    $form     = new ON_QuickForm('SearchProduct', 'get', 'product_search.php');
    $form->hideRequiredNote();
    // add elements
    $form->addElement('text', 'sw', _('Product Name/Detail')); 
    $form->setInlineTemplate('sw');  
    $form->addRule('sw',  _('Please enter required field(s)'), 'required', null, 'client');

    $form->addElement('submit', 'sb', _('Search'), 'class="sb"');
    $form->setInlineTemplate('sb');  
  }

  /**
   * Return stock alarms emails
   *
   */  
  public function getAlarmSubsc() {
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query('SELECT u.email FROM '. DB_TBL_USERS  .' AS u, '.DB_TBL_STOCK_ALARMS.' AS s '.
                                 ' WHERE  u.userid = s.userid AND s.productid='.(int)$this->productid);
      return $result->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
  }

  /**
   * Set stock alarms
   *
   */  
  public function fillStockAlarms() {
    if ($this->oldstockcount==0 && $this->stockcount > 0) {
      $enc = new ON_Enc();
      $quemail = new ON_QueMail();
      $mail_values = $this->getAlarmSubsc();
      $queid = $mailcount = 0;
      if (is_array($mail_values)) {
        foreach($mail_values as $email) {
          $values = array('mailto'=>ON_Filter($email), 
                          'mailsubject'=>ON_Filter(_('Your Stock Alarm Subscription')),
                          'mailbody'=>ON_Filter('<a href="'.LC_SITE.'detay/'.
                                                addext(createHand($this->productname).'_'.
                                                       $enc->encrypt($this->productid)).'">'.
                                                $this->productname.'</a>')
                          );
          $quemail->setValues($values);
          $quemail->insert($queid);
        }
        $mailcount = count($mail_values);
      }
      return $mailcount;
    }
  }

  /**
   * Filter row values for display purpose
   *
   * @param mixed $row the parameter with reference
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
      $value = get_payment_type($value);
      break;
    case 'paymentstatus':
      $value = get_payment_status($value);
      break;
    case 'productstatus':
      $value = get_product_status($value);
      break;
    case 'dtcreated':
      $value = $this->getDate('ol',$value);
      break;
    }
    return ON_Filter($value);
  }

}


?>