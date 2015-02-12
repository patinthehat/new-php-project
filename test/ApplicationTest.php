<?php


class ApplicationTest extends \PHPUnit_Framework_TestCase
{
  
  public function testApplication() 
  {
    $projectName = "MyProject".mt_rand(10000, 99999);
    $fn = "";    
    if (file_exists("new-php-project.php"))
      $fn = "new-php-project.php";
    if (file_exists("../new-php-project.php"))
      $fn = "../new-php-project.php";    

    //TravisCI support
    if (file_exists("/home/travis/build/patinthehat/new-php-project/new-php-project.php"))
      $fn = "/home/travis/build/patinthehat/new-php-project/new-php-project.php";
    
    if (file_exists($fn)) {
      chdir(dirname(realpath($fn)));
      $cmd = "$fn $projectName";
      $this->assertFalse(project_exists($projectName));
      system($cmd, $retval);
      $this->assertTrue(project_exists($projectName));
      $this->assertEquals(0, $retval);
      
      if (strlen(trim($projectName)) > 1) //prevent deleting the current directory if projectName is empty
        system("rm -rf ./$projectName");
    }
  }
  
}
