<?php

/**
 * Tag table dao object 
 * 
 */  
class ON_Tag extends ON_Dao
{

  /**
   * Pear Quickform ojbect
   * 
   * @var object
   */  
  public $form;

  /**
   * If form values valid true otherwise false for insert/update process
   * 
   * @var boolean
   */  
  public $isFormValid;

  /**
   * array list of comma seperated tags
   */
  private $tags;

  /**
   * Construct
   * 
   */  
  public function __construct($tags=null)
  {
    parent::__construct(array('table'=> DB_TBL_TAGS, 'pk'=>'tagid','seq'=>'tag_nid_seq'),
                        array('tagid','tagname','taghand')
			);
    if ($tags) {
      $this->tags   = array_map('trim', array_unique(explode(",", $tags)));
    }
  }

  /**
   * Paginate with product rows for lists
   *
   * Join with images table
   * 
   * @param object  $pager Pager object for pagination
   * @param integer $numrows The row count of the table
   * @param string  $where Add to query if exists
   * @return boolean True if success otherwise false
   */  

  public function productPager(&$pager, &$numrows, $where=null) {
    $join_extra = $field_extra = '';
    $user =& ON_User::getInstance();
    $userid = $user->getLoginID();    

    if ($userid) {
      $join_extra  = ' LEFT JOIN '.DB_TBL_PRODUCT_FAVORS.' f ON a.productid=f.productid AND f.userid='.(int)$userid;
      $join_extra .= ' LEFT JOIN '.DB_TBL_STOCK_ALARMS.' sa ON a.productid=sa.productid AND sa.userid='.(int)$userid;
      $field_extra = ',f.productid AS favor, sa.productid AS stockalarm';
    }

    $query = 'SELECT a.*,t.tagname, i.hostfilename, i.fileextension'.$field_extra.
      ' FROM ' . DB_TBL_PRODUCT_TAGS . ' rt '. 
      ' INNER JOIN '.DB_TBL_PRODUCTS.' a ON a.productid=rt.productid '. $join_extra.
      ' INNER JOIN '.DB_TBL_TAGS.' t ON rt.tagid=t.tagid ' .
      ' LEFT JOIN '.DB_TBL_IMAGES.' i ON a.productid=i.productid AND i.isdefault=1'.
      ' WHERE (a.isdeleted=0 OR a.isdeleted IS NULL) ';
    if ($where) {$query .= ' AND '.$where;}
    return parent::pager($pager, $numrows, $query);
  }

  /**
   * Get tags
   *
   * @param array $out The tag array
   * @return boolean ture if success
   */  
  public function getTags(&$out) {
    $out =& $this->getAll(); 
    if (is_array($out)) {
      return true;
    }
    return false;
  }

  /**
   * Return tags for specific product
   *
   * @param integer $theid Given product id
   * @return string comma delimited tag lists
   */  
  public function loadProductTags($theid) {
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query('SELECT tagname FROM '. $this->reg['table'] .' AS p, '.DB_TBL_PRODUCT_TAGS.' AS s '.
				 ' WHERE  p.tagid = s.tagid AND s.productid='.(int)$theid . $this->where());
      $rows = implode(', ', $result->fetchAll(PDO::FETCH_COLUMN)); // for input box
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    return $rows;
  }

  /**
   * Register quickform object
   * 
   * @param string $formName The html form name 
   */
  public function registerForm($formName) {
    $this->form     = new ON_QuickForm($formName, 'post');
  }

  /**
   * Fill the tag form
   * 
   */
  public function fillForm() {

    // set form defaults, $defaults array will be used after update/insert process
    $defaults = $this->defaults();
    $this->form->setDefaults($defaults);
    
    // add hidden element to session for controller purpose
    if ($this->tagid > 0) {
      $enc = new ON_Enc();
      $this->form->addElement('hidden', 'id', $enc->encrypt($this->tagid));
    }

    if (isset($_REQUEST['currentpage'])) {
      $this->form->addElement('hidden', 'currentpage', intval($_REQUEST['currentpage']));
    }

    $this->form->addElement('text', 'tagname', _('Tag')); 
    $this->form->addRule('tagname', _('Tag name required'), 'required', null, 'client');

    $this->form->addElement('submit', 'sb', _('Save'), 'class="sb"');
  }

  /**
   * Call quickform form validate
   * if form valid true call setValues
   *
   * @return boolean true if valid
   */  
  public function validate() {
    $this->isFormValid = $this->form->validate();
    if ($this->isFormValid) {
      $values =& $this->form->exportValues();
      $this->setValues($values);      
    }
    return $this->isFormValid;
  }

  /**
   * Insert product tags to database
   * 
   * @param integer $productid The product id
   * @return boolean true if success
   */  
  public function insertProductTags($productid) {

    $result = false;

    if ($productid == 0) return false;

    if (!is_array($this->tags)){ // tags isn't a required element
      return true;
    }
    
    // if exists delete old product-tag relations
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query('DELETE FROM '.DB_TBL_PRODUCT_TAGS.' WHERE productid='.(int)$productid);
    } catch (PDOException $e) {
      try { self::$db->rollBack(); } catch (PDOException $e2) {};
      $this->fatal_error($e->getMessage());
    }

    // find undefined tags
    $tags_in = implode(',', array_map(create_function('$arg','return "\'".trim(ON_Filter($arg))."\'";'),
                                      $this->tags));    

    $data = $tagnames_arr = $tagids = array();
    try {
      if(!self::$db) $this->connect();
      $result = self::$db->query('SELECT tagname, tagid FROM '. $this->reg['table']
                                 .' WHERE tagname IN('.$tags_in.')');
      if ($result) {
        $data = $result->fetchAll(PDO::FETCH_ASSOC); 
      }
    } catch (PDOException $e) {
      try { self::$db->rollBack(); } catch (PDOException $e2) {};
      $this->fatal_error($e->getMessage());
    }    
    if(count($data)) {      
      foreach($data as $key=>$tags) {
        $tagnames_arr[] = $tags['tagname'];
        $tagids[$tags['tagname']] = $tags['tagid'];      
      }
    }
    
    if (is_array($this->tags)) {
      foreach ($this->tags as $tag) {
        if(in_array($tag, $tagnames_arr)) { // tags available in database so use it	  
          $product_tags[] = $tagids[$tag];
        } else {                            // new tags insert them
          $this->setValues(array('tagname'=>$tag));
          $tagid = 0;
          parent::insert($tagid);
          $product_tags[] = $tagid;
        }
      }
    }
    
    // insert product-tag relations again    
    if (count($product_tags)) {
      try {
        if(!self::$db) $this->connect();
        foreach ($product_tags as $tagid) {
          $result = self::$db->query('INSERT INTO ' . DB_TBL_PRODUCT_TAGS .
                                     ' (productid, tagid) VALUES ('.(int)$productid.', '.(int)$tagid.')');
        }
      } catch (PDOException $e) {
        try { self::$db->rollBack(); } catch (PDOException $e2) {};
        $this->fatal_error($e->getMessage());
      }
    }
    return $result;
  }

  /**
   * Set tag values
   * 
   * @param array $values The form values filled by panel user
   */  
  public function setValues($values) {
    $this->tagname = ON_Filter($values['tagname']);
    $this->taghand = createHand($this->tagname);
  }
}

?>