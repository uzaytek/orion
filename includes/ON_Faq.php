<?php

/**
 * Faq table dao object 
 * 
 */  
class ON_Faq extends ON_Dao
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
   * Construct faq
   * 
   */  
  public function __construct() {
    parent::__construct(array('table'=> DB_TBL_FAQS, 'pk'=>'faqid','seq'=>''),
                        array('faqid','faqtitle','faqdetail','isdeleted','dtcreated')
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
   * Fill the faq form
   * 
   */
  public function fillForm() {

    // set form defaults, $defaults array will be used after update/insert process
    $defaults = $this->defaults();
    $this->form->setDefaults($defaults);
    
    // add hidden element to session for controller purpose
    if ($this->faqid > 0) {
      $enc = new ON_Enc();
      $this->form->addElement('hidden', 'id', $enc->encrypt($this->faqid));
    }

    if (isset($_REQUEST['currentpage'])) {
      $this->form->addElement('hidden', 'currentpage', intval($_REQUEST['currentpage']));
    }

    // add elements
    $this->form->addElement('text', 'faqtitle', _('Faq Title')); 
    $this->form->addRule('faqtitle', _('Faq title required'), 'required', null, 'client');

    $this->form->addElement('textarea', 'faqdetail', 'Faq Detail', 'id="faqdetail"');
    $this->form->setRichTextTemplate('faqdetail');
    $this->form->addRule('faqdetail', _('Faq detail required'), 'faqdetail', 'required', 'client');

    $this->form->addElement('submit', 'sb', _('Save'), 'class="sb"');
  }

  /**
   * Set faq values
   * 
   * @param array $values The form values filled by panel user
   */  
  public function setValues(&$values) {
    $this->faqtitle   = ON_Filter($values['faqtitle']);
    $this->faqdetail  = ON_Filter($values['faqdetail']);
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
   * @param integer $faqid The database row id 
   */  
  public function insert(&$faqid) {

    if ($this->isFormValid !== true) {
      return false;
    }

    return parent::insert($faqid);
  }

  /**
   * Update values to db
   *
   * @param integer $faqid The database row id 
   */  
  public function update(&$faqid) {
    if ($this->isFormValid !== true) {
      return false;
    }
    return parent::update($faqid);
  }


}


?>
