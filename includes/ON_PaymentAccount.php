<?php

/**
 * Payment Account table dao object 
 * 
 */  
class ON_PaymentAccount extends ON_Dao
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
   * Construct account
   * 
   */  
  public function __construct() {
    parent::__construct(array('table'=> DB_TBL_PAYMENT_ACCOUNTS, 'pk'=>'accountid','seq'=>''),
                        array('accountid','title','detail',
                              'isdeleted','dtcreated')
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
   * Fill the account form
   * 
   */
  public function fillForm() {

    // set form defaults, $defaults array will be used after update/insert process
    $defaults = $this->defaults();
    $this->form->setDefaults($defaults);
    
    // add hidden element to session for controller purpose
    if ($this->accountid > 0) {
      $enc = new ON_Enc();
      $this->form->addElement('hidden', 'id', $enc->encrypt($this->accountid));
    }

    if (isset($_REQUEST['currentpage'])) {
      $this->form->addElement('hidden', 'currentpage', intval($_REQUEST['currentpage']));
    }

    // add elements
    $this->form->addElement('text', 'title', _('Account Title')); 
    $this->form->addRule('title', _('Account title required'), 'required', null, 'client');

    $this->form->addElement('textarea', 'detail', _('Account Detail')); 
    $this->form->addRule('detail', _('Account detail required'), 'required', null, 'client');

    $this->form->addElement('submit', 'sb', _('Save'), 'class="sb"');
  }

  /**
   * Set account values
   * 
   * @param array $values The form values filled by panel user
   */  
  public function setValues(&$values) {
    $this->title    = ON_Filter($values['title']);
    $this->detail   = ON_Filter($values['detail']);
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
   * @param integer $accountid The database row id 
   */  
  public function insert(&$accountid) {

    if ($this->isFormValid !== true) {
      return false;
    }
    return parent::insert($accountid);
  }

  /**
   * Update values to db
   *
   * @param integer $accountid The database row id 
   */  
  public function update(&$accountid) {
    if ($this->isFormValid !== true) {
      return false;
    }
    return parent::update($accountid);
  }


}


?>
