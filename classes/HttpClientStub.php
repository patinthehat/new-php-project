<?php

class HttpClientStub extends HttpClient
{
  protected $returnData = array();
  protected $responseCode = 200;
  
  function __construct($returnData = array()) 
  {
    $this->setResponseCode(200);
    $this->setReturnData($returnData);
  }

  public function setReturnData($data)
  {
    $this->returnData = $data;
  }
  
  public function setResponseCode($code)
  {
    if (is_numeric($code))
      $this->responseCode = intval($code);
  }
  
  public function getHttpCode()
  {
    return $this->responseCode;
  }
  
  /**
   * @codeCoverageIgnore
   */
  private function _get_returnDataItem($name){
    foreach($this->returnData as $re=>$data) {
      if ($data['name'] == $name)
        return $data;
    }
    return false;
  }
    
  public function get($url, $useSSL = TRUE, $options = array())
  {
    foreach($this->returnData as $re=>$data) {
      if (preg_match($re, $url)==1) {
        $code     = $data['code'];
        $name     = $data['name'];
        $dataStr  = $data['data'];
        $this->setResponseCode($code);
        if ($code == 304) {
          $d = $this->_get_returnDataItem($name);
          if ($d !== false)
            $dataStr = $d['data'];
        }
        return $dataStr;
      }
    }
    return "";
  } 
  
}
