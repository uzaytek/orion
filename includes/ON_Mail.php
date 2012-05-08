<?php

/**
 * Call mail an object way
 * 
 */  
class ON_Mail {

  /**
   * Mail headers
   * 
   * @var string
   */  
  private $headers;

  /**
   * Mail Subject
   * 
   * @var string
   */  
  public $subject;

  /**
   * Mail to
   * 
   * @var string
   */  
  public $to;

  /**
   * Mail body
   * 
   * @var string
   */  
  public $body;

  /**
   * Mail type Used for apart mails
   * 
   * @var string
   */  
  private $type;

  /**
   * Construct
   * 
   */  
  function __construct($type, &$params='') {
    $this->headers .= 'Content-type: text/html; charset=utf-8' . "\n";
    $this->headers .= 'From: ' . EM_ADMIN . ' <' . EM_ADMIN . '>' . "\n";    
    $this->type = $type;

    switch ($type) {
    case 'subscribe':
      $this->to = ON_Filter($params['to']);
      $this->subject = _('E-Bulletin Subscription');
      $this->setBody('Please click the below link for e-bulletin activation.'."\n".
                     LC_SITE.'/approve.php?k='.md5($this->to.'secretsalt'));      
      break;
    case 'contact':
      break;
    }
  }

  /**
   * Set body
   *
   * @param string $body Mail body
   */  
  function setBody($body) {
    $this->body = ON_Filter($body);
  }

  /**
   * Set bcc
   *
   * @param mixed $mBCC string for BCC mail or array BCC mails
   */  
  function setBCC(&$mBCC) {    
    if (is_array($mBCC)) {
      $this->headers = 'BCC: '.implode(',', ON_Filter($mBCC))."\n";
    } else {
      $this->headers = 'BCC: '.ON_Filter($mBCC)."\n";
    }
  }

  /**
   * Send mail
   *
   */  
  function send() {
    if ($this->to && $this->subject && $this->body) {
      mail($this->to, $this->subject, $this->body, $this->headers);
    }
  }
}

?>