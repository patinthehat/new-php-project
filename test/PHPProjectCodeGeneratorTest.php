<?php


class PHPProjectCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{

  public function testGenerate()
  {
    $code = PHPProjectCodeGenerator::generate();
    $this->assertRegExp('/<?php/', $code);
    $this->assertRegExp('/include(.*autoload\.php.*);/', $code);
  }
  
}
