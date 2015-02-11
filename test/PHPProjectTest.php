<?php

require_once('include/utils.php');

class PHPProjectTest extends \PHPUnit_Framework_TestCase
{
  public function testAddClass()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $project->addClass('MyClass1');    
    $this->assertEquals('MyClass1', $project->getClasses()[0]);
    $this->assertEquals(1, count($project->getClasses()));
  }  

  public function testSetClasses()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $project->setClasses(array('MyClass1'));  
    $this->assertEquals('MyClass1', $project->getClasses()[0]);
    $this->assertEquals(1, count($project->getClasses()));
  }
  
  public function testGetClassFilenames()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $project->setClasses(array('MyClass1'));
    //$this->assertEquals('classes/MyClass1.php', $project->getClassFilenames()['classes/MyClass1.php']);
    $this->assertEquals(1, count($project->getClassFilenames()));
  }
  
  public function testAddClassFiles()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $project->setClasses(array('MyClass1'));
    $project->addClassFiles();
    
    $codeTest = $project->generate_class_code('MyClass1');
    $this->assertEquals(md5($codeTest), md5($project->getFiles()['classes/MyClass1.php']));
    $this->assertEquals(1, count($project->getFiles())); 
  }
  
  public function testGenerateClassCode()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH'); 
    $codeTest = $project->generate_class_code('MyClass1');  
    $this->assertRegExp('/class MyClass1/im', $codeTest);
    $this->assertStringMatchesFormat('%Aclass%AMyClass1%A{%A}%A', $codeTest);
  }

  public function testGenerateAutoloaderCode()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $codeTest = $project->generate_autoloader_code();
    $this->assertEquals(1, preg_match('/function _.*_autoload/im', $codeTest));
    $this->assertEquals(1, preg_match('/spl_autoload_register/i', $codeTest));
  }  
  
  public function testGenerateProjectCode()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $codeTest = $project->generate_project_code();
    $this->assertRegExp('/.*<?php.*/im', $codeTest);
  }  
  
  public function testGetFilename()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $this->assertEquals('TESTBASEPATH/TESTNAME/TESTNAME.php', $project->getProjectFilename());    
  }
  
  public function testPreCreateProject()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $this->assertTrue($project->preCreateProject());
    $project->postCreateProject();
  }  
  
}