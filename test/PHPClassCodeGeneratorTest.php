<?php


class PHPClassCodeGeneratorTest extends \PHPUnit_Framework_TestCase
{

  public function testGenerate()
  {
    $code = PHPClassCodeGenerator::generate(null, "MyClass1");
    $this->assertRegExp('/<?php/', $code);
    $this->assertRegExp('/class MyClass1/', $code);
    $this->assertStringMatchesFormat('%Aclass%AMyClass1%A{%A}%A', $code);
  }
  
}
