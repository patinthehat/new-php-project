<?php

class TestableHttpClient extends HttpClient {

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