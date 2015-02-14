<?php


class PHPUnitConfigurationCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
  
  public function testGenerate() 
  {
    $project = new PHPProject("TEST", ".");
    $project->addPath("a");
    $project->addPath("b");
    
    $data = \NPP\CodeGeneration\PHPUnitConfigurationCodeGenerator::generate($project, array('coverage'=>1));
    $this->assertRegExp('/<\?xml version="1.0" encoding="UTF-8"\?>/', $data);
    $this->assertRegExp('/<phpunit/', $data);
    $this->assertRegExp('/<directory suffix=".php">a<\/directory>/', $data);
    $this->assertRegExp('/<directory suffix=".php">b<\/directory>/', $data);    
    $this->assertRegExp('/<\/phpunit>/', $data);
  }
  
}
