<?php

/**
 * News table dao object 
 * 
 */  
class ON_Newsletter extends ON_Dao
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
   * Construct newsletter
   * 
   */  
  public function __construct() {
    parent::__construct(array('table'=> DB_TBL_NEWSLETTERS, 'pk'=>'letterid','seq'=>''),
                        array('letterid','lettersubject','letterbody','isdeleted','dtcreated')
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
   * Fill the newsletter form
   * 
   */
  public function fillForm() {

    // set form defaults, $defaults array will be used after update/insert process
    $defaults = $this->defaults();
    $this->form->setDefaults($defaults);
    
    // add hidden element to session for controller purpose
    if ($this->letterid > 0) {
      $enc = new ON_Enc();
      $this->form->addElement('hidden', 'id', $enc->encrypt($this->letterid));
    }

    if (isset($_REQUEST['currentpage'])) {
      $this->form->addElement('hidden', 'currentpage', intval($_REQUEST['currentpage']));
    }

    // add elements
    $this->form->addElement('text', 'lettersubject', _('Newsletter Subject')); 
    $this->form->addRule('lettersubject', _('Newsletter subject required'), 'required', null, 'client');

    $this->form->addElement('textarea', 'letterbody', 'Newsletter Body', 'id="letterbody"');
    $this->form->setRichTextTemplate('letterbody');
    $this->form->addRule('letterbody', _('Newsletter body required'), 'letterbody', 'required', 'client');

    $this->form->addElement('submit', 'sb', _('Save'), 'class="sb"');
  }

  /**
   * Fill the send a bulletin form
   * 
   */
  public function fillSendForm() {
    // add elements

    if ($this->letterid > 0) {
      $enc = new ON_Enc();
      $this->form->addElement('hidden', 'id', $enc->encrypt($this->letterid));
    }


    $this->form->addElement('text', 'addtomail', _('E-mail'));
    $this->form->setAddNoteTemplate('addtomail',
				    _('If you find a copy of this bulletin please provide an e-mail'), true);
    $this->form->addElement('submit', 'sb', _('Send'), 'class="sb"');
  }

  /**
   * Set newsletter values
   * 
   * @param array $values The form values filled by panel user
   */  
  public function setValues(&$values) {
    $this->lettersubject   = ON_Filter($values['lettersubject']);
    $this->letterbody  = ON_Filter($values['letterbody']);
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
   * @param integer $letterid The database row id 
   */  
  public function insert(&$letterid) {

    if ($this->isFormValid !== true) {
      return false;
    }

    return parent::insert($letterid);
  }

  /**
   * Update values to db
   *
   * @param integer $letterid The database row id 
   */  
  public function update(&$letterid) {
    if ($this->isFormValid !== true) {
      return false;
    }
    return parent::update($letterid);
  }


}


?>
