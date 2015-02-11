<?php

class TestableProjectTest extends \PHPUnit_Framework_TestCase
{
  public function testInit()
  {
    $project = new TestableProject('TESTNAME', 'TESTBASEPATH');        
    $this->assertEquals('TESTNAME', $project->getName());
    $this->assertEquals('TESTBASEPATH', $project->getBasePath());
  }
  
  public function testTargetPath()
  {
    $project = new TestableProject('TESTNAME', 'TESTBASEPATH');
    $this->assertEquals('TESTBASEPATH/TESTNAME', $project->getTargetPath());
  }
  
  public function testAddPath()
  {
    $project = new TestableProject('TESTNAME', 'TESTBASEPATH');
    $project->addPath('PATH1');    
    $this->assertEquals('PATH1', $project->getPaths()[0]);
    $this->assertCount(1, $project->getPaths());
  }  

  public function testSetPaths()
  {
    $project = new TestableProject('TESTNAME', 'TESTBASEPATH');
    $project->setPaths(array('PATH1'));  
    $this->assertEquals('PATH1', $project->getPaths()[0]);
    $this->assertCount(1, $project->getPaths());
  }

  public function testAddFile()
  {
    $project = new TestableProject('TESTNAME', 'TESTBASEPATH');
    $project->addFile(new File('FILE1', '.', 'TESTDATA'));  
    $this->assertTrue(isset($project->getFiles()['FILE1']));
    $this->assertEquals('TESTDATA', $project->getFiles()['FILE1']->getData());
    $this->assertCount(1, $project->getFiles());
  }
    
  public function testSetFiles()
  {
    $project = new TestableProject('TESTNAME', 'TESTBASEPATH');
    $project->setFiles(array(new File('FILE1', '.', 'TESTDATA')));  
    $this->assertTrue(isset($project->getFiles()['FILE1']));
    
    $this->assertEquals('TESTDATA', $project->getFiles()['FILE1']->getData());
    $this->assertCount(1, $project->getFiles());
  }
  
  public function testGetProjectFileName()
  {
    $project = new TestableProject('TESTNAME', 'TESTBASEPATH');
    $this->assertEquals("TESTBASEPATH/TESTNAME/TESTNAME", $project->getProjectFilename());    
  }
  
  public function testCreateProjectPaths()
  {
    $rand = mt_rand(10000, 99999);
    $projectName = "TESTPROJECT$rand";  
      
    $project = new TestableProject($projectName, __DIR__);
    $project->addPath('classes');    
    $this->assertFalse(file_exists($project->getTargetPath()));
    $project->createProjectPaths();    
    $this->assertTrue(file_exists($project->getTargetPath()));
    $this->assertTrue(file_exists($project->getTargetPath()."/classes"));
    rmdir($project->getTargetPath()."/classes");
    rmdir($project->getTargetPath());
  }

  public function testCreateProjectFiles()
  {
    $rand = mt_rand(10000, 99999);
    $projectName = "TESTPROJECT$rand";
  
    $project = new TestableProject($projectName, __DIR__);
    $project->addFile(new File("testfile.txt", ".", "TEST FILE"));  
    $this->assertFalse(file_exists($project->getTargetPath()."/testfile.txt"));
    $project->createProjectPaths();
    $project->createProjectFiles();
    $data = file_get_contents($project->getTargetPath()."/testfile.txt");
    $this->assertEquals(md5("TEST FILE"), md5($data));
    $this->assertTrue(file_exists($project->getTargetPath()."/testfile.txt"));
    unlink($project->getTargetPath()."/testfile.txt");
    rmdir($project->getTargetPath());
  }
  
  public function testCreateProject()
  {
    $rand = mt_rand(10000, 99999);
    $projectName = "TESTPROJECT$rand";
  
    $project = new TestableProject($projectName, __DIR__);
    $project->addFile(new File("testfile.txt", ".", "TEST FILE"));
    $project->addPath('classes');
    $this->assertFileNotExists($project->getTargetPath());
    $this->assertTrue($project->createProject());
    unlink($project->getTargetPath()."/testfile.txt");
    rmdir($project->getTargetPath()."/classes");
    rmdir($project->getTargetPath());    
  }
  
}