<?php

namespace NPP\Http;

class TestableHttpClient extends \NPP\Http\HttpClient
{

  public function setData($data)
  {
    parent::setData($data);
  }
  
  public function setHttpCode($code)
  {
    parent::setHttpCode($code);
  }

  public function getHeaderHandler($ch, $header) 
  {
    parent::getHeaderHandler($ch, $header);
  }
  
}