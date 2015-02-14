<?php
      
class HttpClientTest extends \PHPUnit_Framework_TestCase
{
  protected $http;
  
  function setUp()
  {
    $this->http = new \NPP\Http\TestableHttpClient();
  }

  public function testGetHttpCode()
  {
    $this->http->reset();
    $this->assertEquals(0, $this->http->getHttpCode());
  }
  
  public function testSetHttpCode()
  {
    $this->http->reset();
    $this->assertEquals(0, $this->http->getHttpCode());
    $this->http->setHttpCode(200);
    $this->assertEquals(200, $this->http->getHttpCode());
    $this->http->setHttpCode("ABC");
    $this->assertEquals(200, $this->http->getHttpCode());    
  }
  
  public function testHasErrorCode()
  {
    $this->http->reset();
    $this->assertFalse($this->http->hasErrorCode());
    $this->http->setHttpCode(404);
    $this->assertTrue($this->http->hasErrorCode());
    $this->http->setHttpCode(501);
    $this->assertTrue($this->http->hasErrorCode());
  }
  
  public function testHasSuccessCode()
  {
    $this->http->reset();
    $this->http->setHttpCode(200);
    $this->assertTrue($this->http->hasSuccessCode(true));
    $this->http->setHttpCode(201);
    $this->assertTrue($this->http->hasSuccessCode(false));
    $this->http->setHttpCode(304);
    $this->assertTrue($this->http->hasSuccessCode(false));    
  }

  public function testGetData()
  {
    $this->http->reset();
    $this->assertFalse($this->http->getData());
    $this->http->get("file://".__FILE__);
    $this->assertEquals(file_get_contents(__FILE__), $this->http->getData());
  }
  
  public function testGetAndSetData()
  {
    $this->http->reset();
    $data = "TEST_DATA_".mt_rand(1000,99999);
    $this->http->setData($data);
    $this->assertEquals($data, $this->http->getData());
    $this->http->setData(false);
    $this->assertFalse($this->http->getData());
  }
  
  public function testHasData()
  {
    $this->http->reset();
    $this->assertFalse($this->http->hasData());
    $this->http->setData("TEST_DATA");
    $this->assertTrue($this->http->hasData());
  }
  
  public function testGetAndSetTimeout()
  {
    $this->http->reset();
    $this->http->setTimeout(5);
    $this->assertEquals(5, $this->http->getTimeout());
    $this->http->setTimeout(1);
    $this->assertEquals(1, $this->http->getTimeout());
    $this->http->setTimeout(false);
    $this->assertEquals(1, $this->http->getTimeout());
    $this->http->setTimeout(null);
    $this->assertEquals(1, $this->http->getTimeout());
  }
  
  public function testGetAndSetUserAgent()
  {
    $this->http->reset();
    $this->http->setUserAgent("TESTUA");
    $this->assertEquals("TESTUA", $this->http->getUserAgent());
    $this->http->setUserAgent("PHP/5.x");
    $this->assertEquals("PHP/5.x", $this->http->getUserAgent());
    $this->http->setUserAgent("");
    $this->assertEquals("", $this->http->getUserAgent());
    $this->http->setUserAgent("ABC");
    $this->http->setUserAgent(false);
    $this->assertEquals("", $this->http->getUserAgent());
    
    $this->assertTrue($this->http->setUserAgent("123"));
    $this->assertFalse($this->http->setUserAgent(123));
  }
  
  public function testGetAndSetResponseHeaders()
  {
    $this->http->reset();
    $this->http->setResponseHeaders(array('a'=>'1','b'=>'2'));
    $this->assertCount(2, $this->http->getResponseHeaders());
    $this->assertEquals(array('a'=>'1','b'=>'2'), $this->http->getResponseHeaders());
    $this->assertEquals('1', $this->http->getResponseHeader('a'));
    $this->assertFalse($this->http->getResponseHeader('ZZZ'));
    $this->http->setResponseHeaders(false);
    $this->assertCount(0, $this->http->getResponseHeaders());
  }
  
  public function testAddAndGetResponseHeader()
  {
    $this->http->reset();    
    $this->assertCount(0, $this->http->getResponseHeaders());
    $this->http->addResponseHeader('X-TEST-1: ABC');
    $this->assertCount(1, $this->http->getResponseHeaders());
    $this->assertEquals('ABC', $this->http->getResponseHeader('X-TEST-1'));
    $this->http->addResponseHeader('X-TEST-2: DEF');
    $this->assertCount(2, $this->http->getResponseHeaders());
    $this->assertEquals('DEF', $this->http->getResponseHeader('X-TEST-2'));    
    $this->http->addResponseHeader('X-TEST-3 GHI');
    $this->assertCount(2, $this->http->getResponseHeaders());
    $this->http->addResponseHeader(false);
    $this->assertCount(2, $this->http->getResponseHeaders());
    $this->assertFalse($this->http->getResponseHeader('X-TEST-FAIL'));    
  }
  
  public function testGetHeaderHandler()
  {
    $this->http->reset();
    $this->http->getHeaderHandler(null, 'X-TEST-1: abcd');
    $this->assertCount(1, $this->http->getResponseHeaders());
    $this->assertEquals('abcd', $this->http->getResponseHeader('X-TEST-1'));
  }
  
  public function testGet()
  {
    
    $this->http->reset();
    $data = $this->http->get("file://".__FILE__);
    $this->assertEquals(file_get_contents(__FILE__), $data);
  }
  
}
