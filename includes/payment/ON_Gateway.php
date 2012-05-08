<?php

class ON_Gateway {

  var $aCCardTypes = array('Visa'=>'Visa','MasterCard'=>'MasterCard');

  /**
   * request payment from bank vpos
   * 
   * post form values to bank vpos and return arrays response
   * 
   * @param double $dTotalPrice The requested price from bank
   * @param string $sErrorMessages The error message reference
   * @param array $aResponse The response array of bank
   * @param string $sOrderKey The order key(id) send to bank
   * @return bool True if success, false otherwise
   */
  function requestPayment($dTotalPrice, &$aResponse, $sOrderKey) {

    return true;

    
    $request = "DATA=<?xml version=\"1.0\" encoding=\"utf-8\"?>
<CC5Request>
<Name>apiclerk</Name>
<Password>clerkpassword</Password>
<ClientId>customerclientid</ClientId>
<IPAddress>".$_SERVER['REMOTE_ADDR']."</IPAddress>
<Mode>P</Mode>
<OrderId>$sOrderKey</OrderId>
<Type>PreAuth</Type>
<Number>".$_POST['card_no']."</Number>
<Expires>".$_POST['group_expdates']['card_expmonth']."/".substr($_POST['group_expdates']['card_expyear'],-2)."</Expires>
<Cvv2Val>".$_POST['card_cv2']."</Cvv2Val>
<Total>".round($dTotalPrice, 2)."</Total>
<UserId></UserId>
<Currency>949</Currency>
<Email></Email>
<BillTo>
<Name></Name>
<Street1></Street1>
<Street2></Street2>
<Street3></Street3>
<City></City>
<StateProv></StateProv>
<PostalCode></PostalCode>
<Country></Country>
<Company></Company>
<TelVoice></TelVoice>
</BillTo>
<ShipTo>
<Name></Name>
<Street1></Street1>
<Street2></Street2>
<Street3></Street3>
<City></City>
<StateProv></StateProv>
<PostalCode></PostalCode>
<Country></Country>
</ShipTo>
<Extra></Extra>
</CC5Request>";

    //echo "<xmp>$request</xmp>";

    
    $cmd="/usr/bin/curl -v -m 120 -d '$request' https://ccpos.garanti.com.tr/servlet/cc5ApiServer -L";
    
    @exec($cmd, $aResponse, $iReturnNumber);

    if (!is_array($aResponse)) {
      return false;
    }

    $message = implode("\n", $aResponse);
    
    if (preg_match("/<Response>(.*?)<\/Response>/si", $message, $match)) {
      $response = $match[1];
      preg_match("/<ProcReturnCode>(.*?)<\/ProcReturnCode>/si", $message, $match);
      $code = $match[1];
      
      preg_match("/<ErrMsg>(.*?)<\/ErrMsg>/si", $message, $match);
      $error = "($response:$code) ".$match[1];
      
      if ($response == "Approved") {
        return true;
      }
    }
    return false;

  }
  
  public function paymentForm(&$user) {
    //$formName, $method='post', $action='', $target='', $attr=null,
    $this->form = new ON_QuickForm('paymentGateway', 'post', '', '', 'class="register"', true); 
    $user->attachBillingForm($this->form);
    $this->attachCCardForm($this->form);
    $this->form->addElement('submit', 'sb', _('Do Payment'));
  }

  public function attachCCardForm(&$form, $aCCardTypes=null) {
    // credit card information

    if ($aCCardTypes == null) {
      $aCCardTypes = $this->aCCardTypes;
    }

    $form->addElement('hidden', 'gateid', $_REQUEST['gateid']);

    $form->addElement('header', 'cc', _('Credit Card'));
    $form->addElement('text', 'paymentowner', _('Credit Card Owner'));
    $form->addRule('paymentowner', _('Credit card owner required'), 'required', null, 'client');
     
    $form->addElement('text', 'ccardnumber', _('Credit Card Number'), array('size' => 15, 'maxlength' => 19)); 
    $form->addRule('ccardnumber', _('Credit card maximum length is 19'), 'maxlength', 19, 'client');
    $form->addRule('ccardnumber', _('Credit card number required'), 'required', null, 'client');
    
    $form->addElement('select', 'ccardtype', _('Card Type'), $aCCardTypes);
    $form->addRule('ccardtype', _('Card type required'), 'required', null, 'client');
      
    $card_expdates[] = $form->createElement('select', 'ccardexpmonth', null, getMonths());
    $card_expdates[] = $form->createElement('select', 'ccardexpyear', null,  getYears());
    $form->addGroup($card_expdates, 'groupexpdates', _('Expiration Date'), '&nbsp;');
    $form->addRule('groupexpdates', _('Expiration date required'), 'required', null, 'client');
      
    $form->addElement('text', 'ccardcv2', _('Card Identification Number'), array('size' => 4, 'maxlength' => 4)); 
    $form->setAddNoteTemplate('ccardcv2', _('Last 3 digits located on the back of the credit card'), true);
    $form->addRule('ccardcv2', _('Card identification number maximum length is 4'),
                   'maxlength', 4, 'client');
    $form->addRule('ccardcv2',  _('Card identification number required'), 'required', null, 'client');

    $form->addElement('textarea', 'paymentnote', _('Note'), array('cols'=>40, 'rows'=>2));


  }



}

?>