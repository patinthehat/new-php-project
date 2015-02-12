<?php


class ApplicationTest extends \PHPUnit_Framework_TestCase
{
  
  public function testApplication() 
  {
    $projectName = "MyProject".mt_rand(10000, 99999);
    //chdir(dirname(__FILE__)."/..");
    if (file_exists("new-php-project.php"))
      $fn = "new-php-project.php";
    if (file_exists("/home/travis/build/patinthehat/new-php-project/new-php-project.php"))
      $fn = "/home/travis/build/patinthehat/new-php-project/new-php-project.php";
    
    chdir(realpath($fn));
    if (file_exists($fn)) {
      $cmd = "$fn $projectName";
      $this->assertFalse(project_exists($projectName));
      system($cmd);
      $this->assertTrue(project_exists($projectName));
      system("rm -rf ./$projectName");
    }
  }
  
}
