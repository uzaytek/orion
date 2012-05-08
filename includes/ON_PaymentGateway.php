<?php

/**
 * Payment Gateway table dao object 
 * 
 */  
class ON_PaymentGateway extends ON_Dao
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
   * Construct gateways
   * 
   */  
  public function __construct() {
    parent::__construct(array('table'=> DB_TBL_PAYMENT_GATEWAYS, 'pk'=>'gateid','seq'=>''),
                        array('gateid','title','username','passwd','clientid',
                              'testurl','realurl','istestmode','isactive',
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
   * Fill the gateway form
   * 
   */
  public function fillForm() {

    // set form defaults, $defaults array will be used after update/insert process
    $defaults = $this->defaults();
    $this->form->setDefaults($defaults);
    
    // add hidden element to session for controller purpose
    if ($this->gateid > 0) {
      $enc = new ON_Enc();
      $this->form->addElement('hidden', 'id', $enc->encrypt($this->gateid));
    }

    if (isset($_REQUEST['currentpage'])) {
      $this->form->addElement('hidden', 'currentpage', intval($_REQUEST['currentpage']));
    }

    // add elements
    $this->form->addElement('text', 'title', _('Gateway Title')); 
    $this->form->addRule('title', _('Payment gateway title required'), 'required', null, 'client');

    $this->form->addElement('text', 'username', _('Username')); 
    $this->form->addRule('username', _('Username required'), 'required', null, 'client');

    $this->form->addElement('text', 'passwd', _('Password')); 
    $this->form->addRule('passwd', _('Password required'), 'required', null, 'client');

    $this->form->addElement('text', 'clientid', _('Client ID')); 
    $this->form->addRule('clientid', _('Client ID required'), 'required', null, 'client');

    $this->form->addElement('text', 'testurl', _('Test URL')); 

    $this->form->addElement('text', 'realurl', _('Real URL')); 
    $this->form->addRule('realurl', _('Real URL address required'), 'required', null, 'client');

    $this->form->addElement('submit', 'sb', _('Save'), 'class="sb"');
  }

  /**
   * Set gateway values
   * 
   * @param array $values The form values filled by panel user
   */  
  public function setValues(&$values) {
    $this->title    = ON_Filter($values['title']);
    $this->username = ON_Filter($values['username']);
    $this->passwd   = ON_Filter($values['passwd']);
    $this->clientid = ON_Filter($values['clientid']);
    $this->testurl  = ON_Filter($values['testurl']);
    $this->realurl  = ON_Filter($values['realurl']);
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
   * @param integer $gateid The database row id 
   */  
  public function insert(&$gateid) {

    if ($this->isFormValid !== true) {
      return false;
    }
    $this->istestmode = 1;
    $this->isactive   = 0;

    return parent::insert($gateid);
  }

  /**
   * Update values to db
   *
   * @param integer $gateid The database row id 
   */  
  public function update(&$gateid) {
    if ($this->isFormValid !== true) {
      return false;
    }
    return parent::update($gateid);
  }


}


?>
