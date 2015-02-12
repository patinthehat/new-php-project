<?php


class ApplicationTest extends \PHPUnit_Framework_TestCase
{
  
  public function testApplication() 
  {
    $projectName = "MyProject".mt_rand(10000, 99999);
    chdir(dirname(__FILE__)."/..");
    $cmd = "new-php-project.php $projectName";
    $this->assertFalse(project_exists($projectName));
    system($cmd);
    $this->assertTrue(project_exists($projectName));
    system("rm -rf ./$projectName");
  }
  
}
