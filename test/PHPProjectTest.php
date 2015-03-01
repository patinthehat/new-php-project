<?php

namespace NPP;

require_once('include/utils.php');

class PHPProjectTest extends \PHPUnit_Framework_TestCase
{
  public function testAddClass()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $project->addClass('MyClass1');    
    $this->assertEquals('MyClass1', $project->getClasses()[0]);
    $this->assertCount(1, $project->getClasses());
  }  

  public function testSetClasses()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $project->setClasses(array('MyClass1'));  
    $this->assertEquals('MyClass1', $project->getClasses()[0]);
    $this->assertCount(1, $project->getClasses());
  }
  
  public function testGetClassFilenames()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $project->setClasses(array('MyClass1'));
    
    $this->assertEquals('classes/MyClass1.php', $project->getClassFilenames()['MyClass1']);
    $this->assertCount(1, $project->getClassFilenames());
  }
  
  public function testAddClassFiles()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $project->setClasses(array('MyClass1'));
    $project->addClassFiles();
    
    $codeTest = \NPP\CodeGeneration\PHPClassCodeGenerator::generate($project, 'MyClass1');
    $this->assertEquals(md5($codeTest), md5($project->getFiles()['classes/MyClass1.php']->getData()));
    $this->assertCount(1, $project->getFiles()); 
  }
  
  public function testAddClassFilesWithGenerateTests()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $project->setClasses(array('MyClass1'));
    $project->addClassFiles(true);  
    $data = \NPP\CodeGeneration\PHPTestCodeGenerator::generate($project, "classes/MyClass1.php");    
    $codeTest = \NPP\CodeGeneration\PHPClassCodeGenerator::generate($project, 'MyClass1');
    $this->assertEquals($data, $project->getFiles()['tests/MyClass1Test.php']->getData());
    $this->assertCount(2, $project->getFiles());
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
  
  public function testPostCreateProject()
  {
    $project = new PHPProject('TESTNAME', 'TESTBASEPATH');
    $project->preCreateProject();
    $this->assertTrue($project->postCreateProject());
  }
    
}