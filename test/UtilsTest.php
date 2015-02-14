<?php
  
require_once('include/utils.php');


class UtilsTest extends \PHPUnit_Framework_TestCase
{

  public function testValidProjectName()
  {
    $s = valid_project_name("RANDOMFILE".mt_rand(100000,999999));
    $this->assertTrue(valid_project_name($s));
    $this->assertFalse(valid_project_name(__FILE__));
    $this->assertFalse(valid_project_name(""));
    $this->assertFalse(valid_project_name(DIRECTORY_SEPARATOR));
    $this->assertFalse(valid_project_name(":"));    
  }

  public function testDashesToUnderscores()
  {
    $s = "test-string";
    $this->assertEquals("test_string", dashes_to_underscores($s));
    $this->assertEquals("test string", dashes_to_underscores("test string"));
  }
  
  public function testSpacesToUnderscores()
  {
    $s = "test string";
    $this->assertEquals("test_string", spaces_to_underscores($s));
    $this->assertEquals("test__string", spaces_to_underscores("test  string"));
    $this->assertEquals("test-string", spaces_to_underscores("test-string"));
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
  
  public function testSafeFilesystemName()
  {
    $this->assertEquals(safe_filesystem_name("good.sh"), "good.sh");
    $this->assertEquals(safe_filesystem_name("ba:d.sh"), "bad.sh");
    $this->assertEquals(safe_filesystem_name("\$myfile.php"), "myfile.php");
  }
  
  public function testFirstChars() 
  {
    $s = "ABCDEF";
    $this->assertEquals("A",    firstChars($s, 1));
    $this->assertEquals("ABC",  firstChars($s, 3));
    $this->assertEquals("",     firstChars(false, 1));
    $this->assertEquals("",     firstChars(null, 1));
  }
  
  public function testProjectExists()
  {
    $projectName = "TESTPROJECT".mt_rand(10000,99999);
    $this->assertFalse(project_exists($projectName));
    mkdir("./$projectName");
    $this->assertTrue(project_exists($projectName));
    rmdir("./$projectName");
  }

  public function testConfigurationFileExists()
  {
    $this->assertEquals(file_exists("new-php-project.json"), configuration_file_exists());
  }
  
  public function testConfigurationDistFileExists()
  {
    $this->assertEquals(file_exists("new-php-project.json.dist"), configuration_dist_file_exists());
  }
  
}
