<?php

class ReadmeMarkdownCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
  
  public function testAddClass()
  {
    $project = new TestableProject('TESTNAME', 'TESTBASEPATH');
    $code = \NPP\CodeGeneration\ReadmeMarkdownCodeGenerator::generate($project);
    
    $this->assertRegExp('/## TESTNAME ##/', $code);
  }

}