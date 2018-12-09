<?php

/**
 * News table dao object 
 * 
 */  
class ON_News extends ON_Dao
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
   * Construct news
   * 
   */  
  public function __construct() {
    parent::__construct(array('table'=> DB_TBL_NEWS, 'pk'=>'newsid','seq'=>''),
                        array('newsid','newstitle','newsdetail','isdeleted','dtcreated')
			);
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
   * Fill the news form
   * 
   */
  public function fillForm() {

    // set form defaults, $defaults array will be used after update/insert process
    $defaults = $this->defaults();
    $this->form->setDefaults($defaults);
    
    // add hidden element to session for controller purpose
    if ($this->newsid > 0) {
      $enc = new ON_Enc();
      $this->form->addElement('hidden', 'id', $enc->encrypt($this->newsid));
    }

    if (isset($_REQUEST['currentpage'])) {
      $this->form->addElement('hidden', 'currentpage', intval($_REQUEST['currentpage']));
    }

    // add elements
    $this->form->addElement('text', 'newstitle', _('News Title')); 
    $this->form->addRule('newstitle', _('News title required'), 'required', null, 'client');

    $this->form->addElement('textarea', 'newsdetail', 'News Detail', 'id="newsdetail"');
    $this->form->setRichTextTemplate('newsdetail');
    $this->form->addRule('newsdetail', _('News detail required'), 'newsdetail', 'required', 'client');

    $this->form->addElement('submit', 'sb', _('Save'), 'class="sb"');
  }

  /**
   * Set news values
   * 
   * @param array $values The form values filled by panel user
   */  
  public function setValues(&$values) {
    $this->newstitle   = ON_Filter($values['newstitle']);
    $this->newsdetail  = ON_Filter($values['newsdetail']);
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
      $this->setValues($values);      
    }
    return $this->isFormValid;
  }

  /**
   * Insert values to db
   *
   * @param integer $newsid The database row id 
   */  
  public function insert(&$newsid) {

    if ($this->isFormValid !== true) {
      return false;
    }

    return parent::insert($newsid);
  }

  /**
   * Update values to db
   *
   * @param integer $newsid The database row id 
   */  
  public function update(&$newsid) {
    if ($this->isFormValid !== true) {
      return false;
    }
    return parent::update($newsid);
  }


}


?>
