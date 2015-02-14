<?php

namespace NPP\Http;

class HttpClient
{
  const DEFAULT_TIMEOUT = 10;
  protected $httpCode = 0;
  protected $data = false;
  protected $userAgent = "";
  protected $timeout = self::DEFAULT_TIMEOUT;
  protected $responseHeaders = array();
  protected $gzipEncoding = false;
  
  function __construct($timeout = -1, $userAgent = "") 
  {
    $this->reset();    
    $this->init(($timeout>-1?$timeout:self::DEFAULT_TIMEOUT), $userAgent);
  }
  
  public function reset()
  {
    $this->setHttpCode(0);
    $this->setData(false);
    $this->setUserAgent("");
    $this->setTimeout(self::DEFAULT_TIMEOUT);
    $this->setResponseHeaders(array());
  }
  
  public function init($timeout, $userAgent)
  {
    $this->setTimeout($timeout);
    $this->setUserAgent($userAgent);
  }
  
  protected function setHttpCode($code)
  {
    if (is_numeric($code))
      $this->httpCode = intval($code);
  }
  
  public function getHttpCode()
  {
    return $this->httpCode;
  }

  public function setGzipEncoding($value)
  {
    if (is_bool($value)) {
      $this->gzipEncoding = $value;
    } else {    
      $this->gzipEncoding = false;
    }
  }
  
  public function getGzipEncoding()
  {
    return $this->gzipEncoding;
  }  
  
  protected function setData($data)
  {
    $this->data = $data;
  }
  
  public function getData()
  {
    return $this->data;  
  }
  
  public function hasData()
  {
    return ($this->data !== false);  
  }
  
  public function setUserAgent($userAgent)
  {
    if (!$userAgent || $userAgent == "") {
      $this->userAgent = "";
      return true;
    }
    if (trim($userAgent) != "" && is_string($userAgent)) {
      $this->userAgent = $userAgent;  
      return true;
    }
    
    return false;
  }
  
  public function getUserAgent()
  {
    return $this->userAgent;
  }
  
  public function setTimeout($timeout)
  {
    if (is_numeric($timeout))
      $this->timeout = intval($timeout);
  }
  
  public function getTimeout() 
  {
    return $this->timeout;
  }
  
  public function setResponseHeaders($headers)
  {
    if (is_array($headers))
      $this->responseHeaders = $headers;
    if ($headers === false || $headers === null)
      $this->responseHeaders = array();
    
    return false;
  }
  
  public function addResponseHeader($header)
  {
    if ($header == "")
      return false;
    
    //skip headers like "HTTP/1.1 200 OK"
    if (preg_match('/HTTP\/\d\.\d [0-9]{3} .*/', $header)==1)
      return false;
    
    //ignore bad strings/bad header formats
    if (strpos($header, ":") === false)
      return false;
    
    list($name, $value) = explode(":", $header);
    if (trim($name)=="")
      return false;
    
    $this->responseHeaders[trim($name)] = trim($value);
    return true;
  }
  
  public function getResponseHeaders()
  {
    return $this->responseHeaders;
  }
  
  public function getResponseHeader($name)
  {
    if (isset($this->responseHeaders[$name]))
      return $this->responseHeaders[$name];
    return false;
  }
  
  public function hasErrorCode()
  {
    $code = $this->getHttpCode();
    if ($code>=400 && $code<=499)
      return true;
    if ($code>=500 && $code<=599)
      return true;
    return false;
  }
  
  public function hasSuccessCode($strict = false)
  {
    if ($strict)
      return $this->getHttpCode() == 200;
    return (in_array($this->getHttpCode(), array(200,201,202,203,204,205,206,304) ));
  }
  
  protected function getHeaderHandler($ch, $header)
  {
    $this->addResponseHeader($header);
    return strlen($header);
  }
  
  public function get($url, $useSSL = TRUE, $newConnectionsOnly = FALSE, $options = array())
  { 
      $defaults = array( 
          CURLOPT_HEADER => 0, 
          CURLOPT_URL => $url, 
          CURLOPT_FRESH_CONNECT => ($newConnectionsOnly ? 1 : 0), 
          CURLOPT_RETURNTRANSFER => 1, 
          CURLOPT_FORBID_REUSE => ($newConnectionsOnly ? 1 : 0), 
          CURLOPT_TIMEOUT => $this->getTimeout(), 
          CURLOPT_AUTOREFERER => 1,
          CURLOPT_USE_SSL => ($useSSL ? 1 : 0),
          CURLOPT_FOLLOWLOCATION => 1,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_USERAGENT => $this->getUserAgent(),
          CURLOPT_HEADERFUNCTION => array($this, 'getHeaderHandler'),
      ); 

      $ch = curl_init(); 
      curl_setopt_array($ch, ($options + $defaults));
      curl_setopt($ch, CURLOPT_ENCODING, ($this->getGzipEncoding() ? 'gzip' : ''));
      
      if( ! $result = curl_exec($ch)) { 
          trigger_error(curl_error($ch)); 
      }
      
      $info = curl_getinfo($ch);
      $this->setHttpCode($info['http_code']);
      curl_close($ch);
      if ($this->getHttpCode() == 304) {
        //if ($this->hasData())
          $result = $this->getData();
      } else {
        $this->setData($result);
      }
      
      return $result; 
  } 
  
}
