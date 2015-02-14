<?php


class PHPAutoloadCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{

  public function testGenerate()
  {
    $project = new TestableProject('TEST', 'BASEPATH');
    $code = \NPP\CodeGeneration\PHPAutoloadCodeGenerator::generate($project);
    $this->assertRegExp('/spl_autoload_register\(/', $code);
    $this->assertRegExp('/function _TEST_autoloader\(/', $code);
  }
  
}
