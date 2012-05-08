<?php

/**
 * Product category dao object
 *
 */ 
class ON_Cat extends ON_Dao
{

  /**
   * Pear Quickform ojbect
   * 
   * @var object
   */  
  public $form;

  /**
   * if submitted form values valid for insert/update process
   *
   * @var boolean
   */
  public $isFormValid; // Is form values valid for insert/update process

  /**
   * Construct 
   */
  public function __construct() {
    parent::__construct(array('table'=> DB_TBL_CATS, 'pk'=>'catid','seq'=>''),
                        array('catid','cattitle','parentcatid','catorder','isdeleted','dtcreated')
			);
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
   * Validate quickform and set values if valid values
   * 
   * @return boolean $isFormValid True if valid
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
   * Insert values to db
   *
   * @param integer $catid The database row id 
   */  
  public function insert(&$catid) {
    if ($this->isFormValid !== true) {
      return false;
    }
    return parent::insert($catid);
  }


  /**
   * Update values to db
   *
   * @param integer $catid The database row id 
   */  
  public function update(&$catid) {
    if ($this->isFormValid !== true) {
      return false;
    }
    return parent::update($catid);
  }

  /**
   * Update category order values according to catid=>catorder
   * 
   * @param boolean true if success
   */  
  public function updateCatOrders($values) {
    try {
      if(!self::$db) $this->connect();
      if (is_array($values)) {
        $enc = new ON_Enc();
        foreach($values as $k=>$v) {
          $catid = (int)$enc->decrypt($k);
          $result = self::$db->exec('UPDATE ' .  $this->reg['table'] .
                                    ' SET catorder = '.intval($v).'  WHERE catid='.(int)$catid);
        }
        return true;
      }
    } catch (PDOException $e) {
      $this->fatal_error($e->getMessage());
    }
    return false;
  }


  /**
   * Fill the category form
   * 
   */
  public function fillForm() {

    // set form defaults, $defaults array will be used after update/insert process
    $defaults = $this->defaults();
    $this->form->setDefaults($defaults);
    
    // add hidden element to session for controller purpose
    if ($this->catid > 0) {
      $enc = new ON_Enc();
      $this->form->addElement('hidden', 'id', $enc->encrypt($this->catid));
    }

    if (isset($_REQUEST['currentpage'])) {
      $this->form->addElement('hidden', 'currentpage', intval($_REQUEST['currentpage']));
    }

    // add elements
    $this->form->addElement('text', 'cattitle', _('Category Title')); 
    $this->form->addRule('cattitle', _('Category title required'), 'required', null, 'client');

    $this->form->addElement('select', 'parentcatid', _('Parent Category'), array(0=>'--')+$this->getCatTitles()); 

    $this->form->addElement('submit', 'sb', _('Save'), 'class="sb"');
  }

  /**
   * Return all category values in array or specific category title if given
   *
   * @param integer $catid Specific category id if given
   * @return array The category/ies
   */  
  public function getCats($catid=0) {
    $where = ($catid > 0) ? ' AND catid='.$catid : '';
    $this->setWhere('(isdeleted=0 OR isdeleted IS NULL)'.$where);

    $rows = parent::getAll();
    $out = array();
    if (is_array($rows)) {      
      foreach($rows as $row) {
        $out[$row['catid']] = $row['cattitle'];
      }
    }
    return $out;
  }  

  /**
   * return category array for select elements
   * 
   */
  public function getCatTitles(){

    $rows = $this->getIndentCats(0);

    if (is_array($rows)) {      
      foreach($rows as $k=>$v) {
        $out[$k] = @str_repeat( "-", $v['level']*2 ) . $v['cattitle'];
      }
    }
    return $out;
  }

  /**
   * Cat titles indent according to level
   * 
   * gets cat[catid] = cat values but add '--' cattitle name according to its level
   * makes recursively calls itself use it carefully
   * 
   * @param integer $parent Parent category id
   * @param integer $level Show level sign according to level parameter
   * @return array Category level tree
   */
  function getIndentCats($parent, $level=0) {
    static $aCats, $aRet;

    // first time generate category array [catid]=category info
    if ($aCats == null) {
      $this->setOrder('parentcatid,catorder,cattitle');
      $this->setWhere('(isdeleted=0 OR isdeleted IS NULL)');
      $tmp = $this->getAll();
      foreach($tmp as $category) { 
        $p = $category['catid'];
        $aCats[$p]= $category;
      }
    }
    
    $tmp = $aCats;
    if (is_array($tmp)) {		
      reset($tmp);
      // for each array value generate category level tree
      foreach($tmp as $k=>$v ) {
        if($v['parentcatid'] == $parent ) {
          // top level,parent 0	 
          // print - according to level
          $catid = $v['catid'];
          $aRet[$catid]['level'] = $level;
          $aRet[$catid]['cattitle'] = $v['cattitle']; // @str_repeat( "-", $level*2 ) 
          $aRet[$catid]['catorder'] = $v['catorder']; // for admin/list_categories catorder element
          $this->getIndentCats($catid, $level +1);
        }
      }
    }
    return $aRet;
  }

  /**
   * Set news values
   * 
   * @param array $values The form values filled by panel user
   */  
  public function setValues(&$values) {
    $this->cattitle   = ON_Filter($values['cattitle']);
    // a category musnt set itself as a parent
    $this->parentcatid = ($this->catid == $values['parentcatid']) ? 0 : (int)$values['parentcatid'];
  }

}

?>
