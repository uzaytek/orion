<?php

class ON_Account {
  var $form;
  var $isFormValid;

  public function paymentForm(&$user) {
    $this->form = new ON_QuickForm('EFTPayment', 'post', '', '', 'class="register"', true); 
    $user->attachBillingForm($this->form);

    $this->form->addElement('header', 'eft', _('Payment Account')); 

    $this->form->addElement('hidden', 'accountid', ON_Filter($_REQUEST['accountid']));

    $enc = new ON_Enc();
    $id = (int)$enc->decrypt(ON_Filter($_REQUEST['accountid']));
    $account  = new ON_PaymentAccount();
    $bload = $account->load($id);
    
    $el = $this->form->addElement('static', 'atitle', _('Account'));
    $el->setValue($account->detail);

    $this->form->addElement('text', 'paymentowner', _('Sender Name'));
    $this->form->addRule('paymentowner', _('Sender name requried'), 'required', null, 'client');

    $this->form->addElement('text', 'paymentdate', _('Send Date'),'id="paymentdate"');
    $this->form->setDateTemplate('paymentdate');
    $this->form->addRule('paymentdate', _('Send date requried'), 'required', null, 'client');
    
    $this->form->addElement('textarea', 'paymentnote', _('Note'), array('cols'=>40, 'rows'=>2));
    
    $this->form->addElement('submit', 'sb', _('Send'), 'class="mi"');
  }
}

?>