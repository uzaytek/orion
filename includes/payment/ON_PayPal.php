<?php

require_once PT_INCLUDE . 'payment/ON_CCard.php';
require_once 'PayPal.php';
require_once 'PayPal/Profile/Handler/Array.php';
require_once 'PayPal/Profile/API.php';
require_once 'PayPal/Type/BasicAmountType.php';

// Add all of the files for direct payment
require_once 'PayPal/Type/DoDirectPaymentRequestType.php';
require_once 'PayPal/Type/DoDirectPaymentRequestDetailsType.php';
require_once 'PayPal/Type/DoDirectPaymentResponseType.php';
require_once 'PayPal/Type/PaymentDetailsType.php';
require_once 'PayPal/Type/AddressType.php';
require_once 'PayPal/Type/CreditCardDetailsType.php';
require_once 'PayPal/Type/PayerInfoType.php';
require_once 'PayPal/Type/PersonNameType.php';

// Add all of the files for express checkout
require_once 'PayPal/Type/SetExpressCheckoutRequestType.php';
require_once 'PayPal/Type/SetExpressCheckoutRequestDetailsType.php';
require_once 'PayPal/Type/SetExpressCheckoutResponseType.php';
require_once 'PayPal/Type/GetExpressCheckoutDetailsRequestType.php';
require_once 'PayPal/Type/GetExpressCheckoutDetailsResponseDetailsType.php';
require_once 'PayPal/Type/GetExpressCheckoutDetailsResponseType.php';
require_once 'PayPal/Type/DoExpressCheckoutPaymentRequestType.php';
require_once 'PayPal/Type/DoExpressCheckoutPaymentRequestDetailsType.php';
require_once 'PayPal/Type/DoExpressCheckoutPaymentResponseType.php';


// Other response types
require_once 'PayPal/Type/ErrorType.php';
require_once 'PayPal/Type/AbstractResponseType.php';
require_once 'PayPal/Type/RefundTransactionResponseType.php';
require_once 'PayPal/Type/TransactionSearchResponseType.php';
require_once 'PayPal/Type/GetTransactionDetailsResponseType.php';
require_once 'PayPal/Type/DoCaptureResponseDetailsType.php';
require_once 'PayPal/Type/DoCaptureResponseType.php';
require_once 'PayPal/Type/DoVoidResponseType.php';

// Ack related constants
define('ON_PAYPAL_ACK_SUCCESS', 'Success');
define('ON_PAYPAL_ACK_SUCCESS_WITH_WARNING', 'SuccessWithWarning');

// Refund related constants
define('ON_PAYPAL_REFUND_PARTIAL', 'Partial');
define('ON_PAYPAL_REFUND_FULL', 'Full');

// Profile
define('ON_PAYPAL_PROFILE', 'ANPayPalProfile');

// express checkout paypal url
define('ON_PAYPAL_EXPRESS_CHECKOUT_ENVIRONMENT','');
define('ON_PAYPAL_EXPRESS_CHECKOUT_URL', 'https://www' . ON_PAYPAL_EXPRESS_CHECKOUT_ENVIRONMENT . '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=');

/**
 * static paypal class that handle paypal process
 * 
 */
class ON_PayPal
{

  var $aCCardTypes = array('Visa'=>'Visa','MasterCard'=>'MasterCard','Discover'=>'Discover','Amex'=>'American Express');
    /**
     * load paypal api profile object
     *
	 * @param ON_DBTran &$oDBTran  transaction object instance
	 * @param APIProfile $oProfile The PayPal profile object
	 * @return bool true on success, false on failure
     */
    function loadProfile(&$oDBTran, &$oProfile){
		
		if($_SESSION[ON_PAYPAL_PROFILE]) {
			$oProfile = $_SESSION[ON_PAYPAL_PROFILE];
			return true;
		}
		
        $handler =& ProfileHandler_Array::getInstance(array(
                 'username' => ON_PAYPAL_USERNAME,
                 'certificateFile' => null,
                 'subject' => null,
                 'environment' => ON_PAYPAL_ENVIRONMENT ));
                 
        $pid = ProfileHandler::generateID();
   
        $oProfile = new APIProfile($pid, $handler);
        $oProfile->setAPIUsername(ON_PAYPAL_USERNAME);
        $oProfile->setAPIPassword(ON_PAYPAL_PASSWORD);
        $oProfile->setSignature(null);
        if (substr(ON_PAYPAL_CERT_FILE, 0, 1)=="/"){ $sCertFile = ON_PAYPAL_CERT_FILE; }
        else{ $sCertFile = ON_Admin_Common_File_Directory().'/'.ON_PAYPAL_CERT_FILE; }
        $oProfile->setCertificateFile($sCertFile);
        $oProfile->setEnvironment(ON_PAYPAL_ENVIRONMENT);         
        // Save our profile to the session
        $_SESSION[ON_PAYPAL_PROFILE] = $oProfile;
		return true;
    }

    function directPaymentForm(&$user) {
      //$formName, $method='post', $action='', $target='', $attr=null,
      $this->form     = new ON_QuickForm('DirectPayment');

      // set form defaults, $defaults array will be used after update/insert process      
      $this->form->setDefaults(array());
      
      // add elements
      $this->form->addElement('header', 'cc',_('PayPal Express Checkout'));
      $this->form->addElement('html',$this->form->withTR('<a href="paypal_express.php"><img src="'.LC_SITE.'/assets/PayPal_mark_37x23.gif" border="0" hspace="4" vspace="0"></a><span style="font-size:11px; font-family: Arial, Verdana;">'._('Save time. Checkout securely. Pay without sharing your financial information.').'</span>'));

      $user->attachBillingForm($this->form);
      $ccard = new ON_CCard;
      $ccard->attachCCardForm($this->form, $this->aCCardTypes);

      $this->form->addElement('submit', 'sb', _('Send'), 'class="mi"');
    }

    function expressCheckoutForm(&$user) {
      //$formName, $method='post', $action='', $target='', $attr=null,
      $this->form     = new ON_QuickForm('ExpressCheckout', 'post','', '', 'class="register"', true);

      // set form defaults, $defaults array will be used after update/insert process      
      $this->form->setDefaults(array());
      
      // add elements
      $user->attachPaymentForm($this->form, $this->aCCardTypes);
      $this->form->addElement('submit', 'sb', _('Express Checkout'), 'class="mi"');

    }
    
    /**
     * set post/request values and populate the paypal request object
     * 
     * @param ON_DBTran &$oDBTran  transaction object instance
     * @param DoDirectPaymentRequestDetailsType $oPayPalRequest the paypal request object
     * @param currency PaymentAmount
     * @return boolean if true, false on error
     */
    function setDirectPaymentRequest(&$oDBTran, &$oPayPalRequest, $ccPaymentAmount) {
		//init
		$aNewSignUp =& $_SESSION['NEW_SIGNUP'];
		
        $oPayPalRequest = PayPal::getType('DoDirectPaymentRequestType');
        if (PayPal::isError($oPayPalRequest)) {
           $oDBTran->Fail('Error in creating direct payment request');
           return false;
        }

        // Populate SOAP request information
        // Payment details
        $orderTotal =& PayPal::getType('BasicAmountType');
        if (PayPal::isError($orderTotal)) {
           $oDBTran->Fail('Error in creating direct payment basic amount type');
           return false;
        }
        
        $orderTotal->setattr('currencyID', 'USD');
        $orderTotal->setval(ON_PayPal::formatAmount($ccPaymentAmount), 'iso-8859-1');
        $paymentDetails =& PayPal::getType('PaymentDetailsType');
        $paymentDetails->setOrderTotal($orderTotal);
        
        $shipTo =& PayPal::getType('AddressType');
        $shipTo->setName($aNewSignUp['txtFirstName'].' '.$aNewSignUp['txtLastName']);
        $shipTo->setStreet1($aNewSignUp['txtAddress1']);
        $shipTo->setStreet2($aNewSignUp['txtAddress2']);
        $shipTo->setCityName($aNewSignUp['txtCity']);
        $shipTo->setStateOrProvince($aNewSignUp['txtState']);
        $shipTo->setCountry($aNewSignUp['selCountry']);
        $shipTo->setPostalCode($aNewSignUp['txtZip']);
        $paymentDetails->setShipToAddress($shipTo);
        
        $dpDetails =& PayPal::getType('DoDirectPaymentRequestDetailsType');
        $dpDetails->setPaymentDetails($paymentDetails);
        
        // Credit Card info
        $cardDetails =& PayPal::getType('CreditCardDetailsType');
        $cardDetails->setCreditCardType($aNewSignUp['selCreditCardType']);
        $cardDetails->setCreditCardNumber($aNewSignUp['txtCreditCardNumber']);
        // Month must be padded with leading zero
        // $padDateMonth = str_pad($expDateMonth, 2, '0', STR_PAD_LEFT);
        $cardDetails->setExpMonth($aNewSignUp['selExpDateMonth']);
        // $card_details->setExpMonth('01');
        $cardDetails->setExpYear($aNewSignUp['selExpDateYear']);
        // $card_details->setExpYear('2010');
        $cardDetails->setCVV2($aNewSignUp['txtCvv2Number']);
        
        $payer =& PayPal::getType('PayerInfoType');
        $personName =& PayPal::getType('PersonNameType');
        $personName->setFirstName($aNewSignUp['txtFirstName']);
        $personName->setLastName($aNewSignUp['txtLastName']);
        $payer->setPayerName($personName);
        $payer->setPayerCountry($aNewSignUp['selCountry']);
        $payer->setAddress($shipTo);
        $cardDetails->setCardOwner($payer);
        $dpDetails->setCreditCard($cardDetails);
        $dpDetails->setIPAddress($_SERVER['SERVER_ADDR']);
        $dpDetails->setPaymentAction('Sale');
        
        $oPayPalRequest->setDoDirectPaymentRequestDetails($dpDetails);
		return true;
    }
    
    /**
     * Sends payment request to paypal and assign response array if success
     * otherwise return false and set error messages
     *
	 * @param ON_DBTran &$oDBTran The transaction object instance
	 * @param APIProfile &$oProfile The Paypal api profile
	 * @param currency $ccPaymentAmount Requested amount
	 * @param &$aPaymentResponse The paypal response array
	 * @return boolean true on success, false on failure
     */    
    function getDirectPaymentResponse(&$oDBTran, &$oProfile, $ccPaymentAmount, &$aPaymentResponse){
	
        $caller =& PayPal::getCallerServices($oProfile);
        
        // get payment request object
        if(!ON_PayPal::setDirectPaymentRequest($oDBTran, $oPayPalRequest, $ccPaymentAmount)) {
            $oDBTran->Fail('Error in creating direct payment request object');
            return false;
        }
        
        // Execute SOAP request
        $response = $caller->DoDirectPayment($oPayPalRequest);
		//investigate SOAP_Fault, $caller DoDirectPayment return SOAP_Fault ojbect if an error
        switch($response->getAck()) {
            case ON_PAYPAL_ACK_SUCCESS:
            case ON_PAYPAL_ACK_SUCCESS_WITH_WARNING:
                $aPaymentResponse['paypalTranID'] = $response->getTransactionID();
                $aPaymentResponse['avsCode'] = $response->getAVSCode();
                $aPaymentResponse['cvv2'] = $response->getCVV2Code();
                $oAmount = $response->getAmount();
                $amount = $oAmount->_value;
                $currencyID = $oAmount->_attributeValues['currencyID'];
                $aPaymentResponse['amountDisplay'] = $currencyID.' '.$amount;
                return true;
                break;           
            default:
                $oDBTran->Fail('Direct Payment Paypal API has returned an error:'.ON_PayPal::htmlPaymentAPIError($response, $aPaymentResponse));
                return false;
                break;
        }
		return false;
    }
    
    /**
     * Return payment error html string
     *
     * @param PayPalResponseObject $response The paypal response object
     * @param array &$aPaymentResponse The paypal response array
     * @return string Error description details
     */
    function htmlPaymentAPIError(&$response, &$aPaymentResponse) {
        $ack           = $response->getAck();
        $correlationID = $response->getCorrelationID();
        $version       = $response->getVersion();        
        $errorList     = $response->getErrors();
        
        $output = "
            <table>
                <tr>
                    <td>Ack:</td>
                    <td>$ack</td>
                </tr>
                <tr>
                    <td>Correlation ID:</td>
                    <td>$correlationID</td>
                </tr>
                <tr>
                    <td>Version:</td>
                    <td>$version</td>
                </tr>";

        if(!is_array($errorList)) {
           $errorCode    = $errorList->getErrorCode();
           $shortMessage = $errorList->getShortMessage();
           $longMessage  = $errorList->getLongMessage();
           $aPaymentResponse['LongMessage'] .= $longMessage;
            $output .= "
            <tr>
                <td>Error Number:</td>
                <td>$errorCode</td>
            </tr>
            <tr>
                <td>Short Message:</td>
                <td>$shortMessage</td>
            </tr>
            <tr>
                <td>Long Message:</td>
                <td>$longMessage</td>
            </tr>";
            
        } else {
            for($n = 0; $n < sizeof($errorList); $n++) {
                $oneError = $errorList[$n];
                $errorCode    = $oneError->getErrorCode();
                $shortMessage = $oneError->getShortMessage();
                $longMessage  = $oneError->getLongMessage();
                $aPaymentResponse['LongMessage'] .= $longMessage;
				
                $output .= "
                <tr>
                    <td>Error Number:</td>
                    <td>$errorCode</td>
                </tr>
                <tr>
                    <td>Short Message:</td>
                    <td>$shortMessage</td>
                </tr>
                <tr>
                    <td>Long Message:</td>
                    <td>$longMessage</td>
                </tr>";            
            } // for
        } // if
            
        $output .= "</table>";
        return $output;
    }
    
    /**
     * set post/request values and populate the paypal request object for express checkout
     * 
     * @param SetExpressCheckoutRequestType &$oPayPalRequest Paypal request object
     * @param string $sType Serviceplan type must be billable
     * @param integer $servicePlanID
     * @param currency $ccPaymentAmount Requested amount
     * @return bool true on success false on failure
     */
    function setExpressCheckoutRequest(&$oPayPalRequest, $sType, $servicePlanID, $ccPaymentAmount) {
        // SetExpressCheckout handling
        $serverName = $_SERVER['SERVER_NAME'];
        // Use this to test with NAT
        // $serverName = '192.168.1.10';
        $serverPort = $_SERVER['SERVER_PORT'];
        
        $aPathParts = pathinfo($_SERVER['SCRIPT_NAME']);
        $sPathInfo = $aPathParts['dirname'];
        //this should be https
        $url='https://'.$serverName.':'.$serverPort.$sPathInfo;
        
        $paymentAmount = ON_PayPal::formatAmount($ccPaymentAmount);
        $currencyCodeType = 'USD';
        // paymentType is ActionCodeType in ASP SDK
        $paymentType='Sale';
        $returnURL = $url.'/signup'.ANExt().'?paymentAmount='.$paymentAmount.'&currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType.'&servicePlan='.$sType.'&ID='.$servicePlanID;
        $cancelURL = $url.'/services'.ANExt();
        
        $oPayPalRequest = PayPal::getType('SetExpressCheckoutRequestType');
        
        $ecDetails =& PayPal::getType('SetExpressCheckoutRequestDetailsType');
        $ecDetails->setReturnURL($returnURL);
        $ecDetails->setCancelURL($cancelURL);
        $ecDetails->setPaymentAction($paymentType);
         
        $orderTotal =& PayPal::getType('BasicAmountType');
        $orderTotal->setattr('currencyID', $currencyCodeType);
        $orderTotal->setval($paymentAmount, 'iso-8859-1');  
        $ecDetails->setOrderTotal($orderTotal);
        
        $oPayPalRequest->setSetExpressCheckoutRequestDetails($ecDetails);
		return true;
    }
    
    /**
     * redirect user to paypal for express checkout
     *
	 * @param APIProfile &$oProfile The Paypal api profile
     * @param string &$outError If an error occurs this variable will be populated
     * @param string $sType Serviceplan type must be billable
     * @param integer $servicePlanID
     * @param currency $ccPaymentAmount Requested amount
     * @return mixed null(relocate to paypal)/string error
     */
    function reDirectToPayPal(&$oProfile, &$outError, $sType, $servicePlanID, $ccPaymentAmount) {
        
        $caller =& PayPal::getCallerServices($oProfile);
        if(ON_PayPal::setExpressCheckoutRequest($oPayPalRequest, $sType, $servicePlanID, $ccPaymentAmount)) {
			// Execute SOAP request        
			$response = $caller->SetExpressCheckout($oPayPalRequest);
			// $display = print_r($response, true);
			switch($response->getAck()) {
				case ON_PAYPAL_ACK_SUCCESS:
				case ON_PAYPAL_ACK_SUCCESS_WITH_WARNING: 
					// Redirect to paypal.com here
					$token = $response->getToken();
					$payPalURL = ON_PAYPAL_EXPRESS_CHECKOUT_URL . $token;
					header("Location: ".$payPalURL);
					exit;
					break;
				default:
					$outError = 'Redirect to paypal failed:'.ON_PayPal::htmlPaymentAPIError($response, $aPaymentResponse);
					return false;
					break;                
			}
		}
		$outError = 'Redirect to paypal failed set express checkout object return false';
		return false;        
    }
    
    /**
     * populates express checkout details array
     * PayPal::getCallerServices object have a same name method. i.e. dont mix these
     *
	 * @param APIProfile &$oProfile The Paypal api profile
	 * @param array &$aECDetails The express checkout details array
     * @param string &$outError If an error occurs this variable will be populated
	 * @return bool true on success false on failure
     */    
    function getExpressCheckoutDetails(&$oProfile, &$aECDetails, &$outError) {
        
        $paymentType=$_REQUEST['paymentType'];
        $token = $_REQUEST['token'];
        $paymentAmount=$_REQUEST['paymentAmount'];
        $currencyCodeType=$_REQUEST['currencyCodeType'];
        
        $ecRequest =& PayPal::getType('GetExpressCheckoutDetailsRequestType');
        $ecRequest->setToken($token);
        
        $caller =& PayPal::getCallerServices($oProfile);
     
        // Execute SOAP request
        $response = $caller->GetExpressCheckoutDetails($ecRequest);
        
        switch($response->getAck()) {
            case ON_PAYPAL_ACK_SUCCESS:
            case ON_PAYPAL_ACK_SUCCESS_WITH_WARNING:
                $respDetails = $response->getGetExpressCheckoutDetailsResponseDetails();
                $payerInfo = $respDetails->getPayerInfo();
                $aECDetails['token'] = $token;
                $aECDetails['payerID'] = $payerInfo->getPayerID();
                
                $address = $payerInfo->getAddress();
				$aECDetails['phoneNumber'] = $address->getPhone();
                $aECDetails['street1'] = $address->getStreet1();
                $aECDetails['street2'] = $address->getStreet2();
                $aECDetails['cityName'] = $address->getCityName();
                $aECDetails['stateProvince'] = $address->getStateOrProvince();
                $aECDetails['postalCode'] = $address->getPostalCode();
				$aECDetails['countryCode'] = $address->getCountry();
                $aECDetails['countryName'] = $address->getCountryName();
                $aECDetails['paymentAmount'] = $paymentAmount;
                $aECDetails['orderTotal'] = $currencyCodeType.' '.$paymentAmount;
				return true;
                break;
            default:
                $outError = 'Get express checkout detail failed:'.ON_PayPal::htmlPaymentAPIError($response, $aECDetails);
                return false;
                break;
        }
    }
    
    /**
     * do last express checkout step
     *
	 * @param ON_DBTran &$oDBTran The transaction object instance
	 * @param APIProfile &$oProfile The Paypal api profile
	 * @param currency $ccPaymentAmount Requested amount
	 * @param array &$aPaymentResponse The paypal response array
	 * @return boolean true on success, false on failure
     */    
    function getExpressCheckoutResponse(&$oDBTran, &$oProfile, $ccPaymentAmount, &$aPaymentResponse) {
        
        $ecDetails =& PayPal::getType('DoExpressCheckoutPaymentRequestDetailsType');

        $token = $_REQUEST['token'];
        $payerID = $_REQUEST['payerID'];        
        $ecDetails->setToken($token);
        $ecDetails->setPayerID($payerID);
        $ecDetails->setPaymentAction('Sale');
        
        $orderTotal =& PayPal::getType('BasicAmountType');
        $orderTotal->setattr('currencyID', 'USD');
        $orderTotal->setval(ON_PayPal::formatAmount($ccPaymentAmount), 'iso-8859-1');  
        
        $paymentDetails =& PayPal::getType('PaymentDetailsType');
        $paymentDetails->setOrderTotal($orderTotal);
        
        $ecDetails->setPaymentDetails($paymentDetails);
        
        $ecRequest =& PayPal::getType('DoExpressCheckoutPaymentRequestType');
        $ecRequest->setDoExpressCheckoutPaymentRequestDetails($ecDetails);
        
        $caller =& PayPal::getCallerServices($oProfile);
        
        // Execute SOAP request
        $response = $caller->DoExpressCheckoutPayment($ecRequest);
        
        switch ($response->getAck()) {
            case ON_PAYPAL_ACK_SUCCESS:
            case ON_PAYPAL_ACK_SUCCESS_WITH_WARNING:
                $details = $response->getDoExpressCheckoutPaymentResponseDetails();
                $paymentInfo = $details->getPaymentInfo();
                $aPaymentResponse['paypalTranID'] = $paymentInfo->getTransactionID();                
                $amt_obj = $paymentInfo->getGrossAmount();
                $aPaymentResponse['amount'] = $amt_obj->_value;
                $currencyID = $amt_obj->_attributeValues['currencyID'];
                $aPaymentResponse['displayAmount'] = $currencyID.' '.$aPaymentResponse['amount'];
                return true;                
                break;
            default:
                $oDBTran->Fail('Express Checkout Paypal API has returned an error:'.ON_PayPal::htmlPaymentAPIError($response, $aPaymentResponse));
                return false;
                break;
           
        }
    }
	
	/**
	 * formatAmount according to Paypal
	 *
	 * @param currency $ccAmount Requested Amount
	 * return string Formatted ccAmount
	 */
	function formatAmount($ccAmount) {
		return number_format(bcadd($ccAmount,'0',2),2,'.',',');
	}

	/**
	 * Calls ON_PayPal::getDirectPaymentResponse or ON_PayPal::getExpressCheckoutResponse according to payment option
	 * Only a wrapper for direct payment and express checkout for simplicity
	 *
	 * @param ON_DBTran &$oDBTran The transaction object instance
	 * @param APIProfile &$oProfile The Paypal api profile
	 * @param array &$aPaymentResponse The paypal response array
	 * @param string $paypalOption The payment option equal to DirectPayment or ExpressCheckout
	 * @param currency $ccCreditAmount Requested amount
	 * @return boolean true on success, false on failure
	 */	
	function requestPayment(&$oDBTran, &$oProfile, &$aPaymentResponse, $paypalOption, $ccCreditAmount) {
		switch ($paypalOption) {
			case 'DirectPayment':
				return ON_PayPal::getDirectPaymentResponse($oDBTran, $oProfile, $ccCreditAmount, $aPaymentResponse);
				break;
			case 'ExpressCheckout':
				return ON_PayPal::getExpressCheckoutResponse($oDBTran, $oProfile, $ccCreditAmount, $aPaymentResponse);
				break;
		}
		$oDBTran->Fail('Failed to determine $paypalOption='.$paypalOption);
		return false;
	}
}

?>