<?php


class ApplicationTest extends \PHPUnit_Framework_TestCase
{
  protected $app;
  protected $args = array('./myapp.php', '-A', '-B', '--charlie');
  
  public function setUp()
  {
    $this->app = new \NPP\Application(new \NPP\ArgumentParser($this->args));
  }
  
  public function testGetName() 
  {
    $this->assertEquals('myapp', $this->app->getName());
  }
  
  public function testGetConfigFilename()
  {
    $this->assertEquals('myapp.json', $this->app->getConfigFilename('json'));
  }
  
  public function testErrorMessage()
  {
    ob_start();
    $this->app->errorMessage('TEST_ERROR_MSG');
    $buf = ob_get_contents();
    $this->assertRegExp('/TEST_ERROR_MSG/', $buf);    
    $this->assertRegExp('/Error: /', $buf);
    ob_end_clean();
  }
  
  public function testStatusMessage()
  {
    ob_start();
    $this->app->statusMessage('TEST_STATUS_MSG');
    $buf = ob_get_contents();
    $this->assertRegExp('/TEST_STATUS_MSG/', $buf);
    ob_end_clean();
  }
  
  public function testGetArgumentParser()
  {
    $this->assertTrue(is_a($this->app->argParser(), 'NPP\\ArgumentParser'));  
  }
  
}
