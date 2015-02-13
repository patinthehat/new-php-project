<?php


class PHPUnitConfigurationCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
  
  public function testGenerate() 
  {
    $data = PHPUnitConfigurationCodeGenerator::generate(null, array('coverage'=>1));
    $this->assertRegExp('/<\?xml version="1.0" encoding="UTF-8"\?>/', $data);
    $this->assertRegExp('/<phpunit/', $data);
    $this->assertRegExp('/<\/phpunit>/', $data);
  }
  
}
