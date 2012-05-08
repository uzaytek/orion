<?php

/**
 * Locale class mimics localeconv function
 * 
 * Server values unreliable especially turkish locale
 * so we mimics localeconv function values
 */  
class ON_Locale {

  /**
   * Decimal point seperator
   * 
   * @var string
   */  
  public $decimal_point;

  /**
   * Thousands seperator
   * 
   * @var string
   */  
  public $thousands_sep;


  /**
   * TRUE if currency_symbol precedes a positive value, FALSE if it succeeds one 
   * 
   * @var boolean
   */  
  public $p_cs_precedes;

  /**
   * Local currency symbol (i.e. $)
   * 
   * @var string
   */  
  public $currency_symbol;

  /**
   * Month texts
   * 
   * @var array
   */  
  public $month;


  /**
   * Construct
   * 
   */  
  public function __construct() {
    $lang = getenv("LANG");
    if (strstr($lang, 'tr_TR')) {
      $this->tr_TR();
    } else {
      $this->en_US();
    }
    $this->month = array('01'=>_('January'), '02'=>_('February'), '03'=>_('March'), '04'=>_('April'),
                         '05'=>_('May'), '06'=>_('June'), '07'=>_('July'), '08'=>_('August'),
                         '09'=>_('September'), '10'=>_('October'), '11'=>_('November'), '12'=>_('December'));

  }

  /**
   * set english values
   * 
   */  
  private function en_US() {
    $this->decimal_point = '.';
    $this->thousands_sep = ',';
    $this->p_cs_precedes = true;
    $this->currency_symbol = '$';
  }

  /**
   * set turkish values
   * 
   */  
  private function tr_TR() {
    $this->decimal_point = ',';
    $this->thousands_sep = '.';
    $this->p_cs_precedes = false;
    $this->currency_symbol = 'TL';
  }

}

?>