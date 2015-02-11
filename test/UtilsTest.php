<?php
  
require_once('include/utils.php');


class UtilsTest extends \PHPUnit_Framework_TestCase
{

  public function testValidProjectName()
  {
    $s = valid_project_name("RANDOMFILE".mt_rand(100000,999999));
    $this->assertTrue(valid_project_name($s));
    $this->assertFalse(valid_project_name(__FILE__));
  }

  public function testDashesToUnderscores()
  {
    $s = "test-string";
    $this->assertEquals("test_string", dashes_to_underscores($s));    
  }
  
  public function testSpacesToUnderscores()
  {
    $s = "test string";
    $this->assertEquals("test_string", spaces_to_underscores($s));
  }  
  
  public function testPhpCompatStr()
  {
    $s = "  test-string 123  ";
    $this->assertEquals("test_string_123", php_compat_str($s));
    $this->assertEquals("teststring", php_compat_str('teststring'));
  }
  
  public function testUsage()
  {
    ob_start();
    usage("TESTPROJECTNAME");
    $data = ob_get_contents();
    ob_end_clean();    
    $this->assertRegExp('/TESTPROJECTNAME/', $data);
    $this->assertFalse(trim($data)=="");
  }
  
}
