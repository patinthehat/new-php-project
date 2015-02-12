<?php


class ArgumentTest extends \PHPUnit_Framework_TestCase
{
  
  public function testGetName()
  {
    $arg = new Argument("myarg", 1);
    $this->assertEquals("myarg", $arg->getName());
  }
  
  public function testGetValue()
  {
    $arg = new Argument("myarg", 1);
    $this->assertEquals(1, $arg->getValue());
  }

  public function testSetValue()
  {
    $arg = new Argument("myarg", 1);
    $this->assertEquals(1, $arg->getValue());
    $arg->setValue(255);
    $this->assertEquals(255, $arg->getValue());
    $this->assertNotEquals(1, $arg->getValue());
  }
  
}
