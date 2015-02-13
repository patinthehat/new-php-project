<?php


class ArgumentParserTest extends \PHPUnit_Framework_TestCase
{
  protected $args =
    array( 
      'hasOperands'=>array("./myapp", "-abc", "-D=123", "--test=value1", "--flagA", "--flagB", "op1", "op2"),
      'noOperands'=>array("./myapp", "-abc", "--test=value1", "--flagA"),
    );
        
  protected $ap = array();
  
  function setUp()
  {
    foreach($this->args as $n=>$v) {
      $this->ap[$n] = new ArgumentParser($v);
    }
  }
  
  function tearDown()
  {
    
  }
  
  protected function getArgParser($name)
  {
    return (isset($this->ap[$name]) ? $this->ap[$name] : false);
  }
  
  public function testArgString()
  {
    $ap1 = new ArgumentParser("./app -abc -D=123 --flagA opA opB");
    $ap1->parse();
    $this->assertEquals(5, $ap1->getArgumentCount());
  }  
  
  public function testGetOperandCount()
  {
    $ap1 = $this->getArgParser("hasOperands");
    $ap2 = $this->getArgParser("noOperands");
    $ap1->parse();
    $ap2->parse();
    $this->assertEquals(2, $ap1->getOperandCount());
    $this->assertEquals(0, $ap2->getOperandCount());
  }
  
  public function testGetOperand()
  {
    $ap1 = $this->getArgParser("hasOperands");
    $ap2 = $this->getArgParser("noOperands");
    $ap1->parse();
    $ap2->parse();
    $this->assertEquals("op1", $ap1->getOperand(0));
    $this->assertEquals("op2", $ap1->getOperand(1));
    $this->assertFalse($ap2->getOperand(0));
  }  
  
  public function testAddArgument()
  {
    $ap1 = new ArgumentParser($this->args["hasOperands"]);
    $this->assertEquals(0, $ap1->getArgumentCount());
    $ap1->parse();
    $this->assertEquals(count($this->args['hasOperands'])-1, $ap1->getArgumentCount());
  }
  
  public function testHasArgument()
  {
    $ap1 = $this->getArgParser("hasOperands");
    $ap1->parse();
    $this->assertTrue($ap1->hasArgument('a'));
    $this->assertTrue($ap1->hasArgument('b'));
    $this->assertTrue($ap1->hasArgument('c'));
    $this->assertTrue($ap1->hasArgument('test'));
    $this->assertFalse($ap1->hasArgument('BADFLAG'));
    $this->assertFalse($ap1->hasArgument(''));
    $this->assertFalse($ap1->hasArgument(null));
    $this->assertFalse($ap1->hasArgument(false));
  }
  
  public function testHasOneOfArguments()
  {
    $ap1 = $this->getArgParser("hasOperands");
    $ap1->parse();
    $this->assertTrue($ap1->hasOneOfArguments(array("a","b","c")));
    $this->assertTrue($ap1->hasOneOfArguments("a","b","c"));
    $this->assertTrue($ap1->hasOneOfArguments(array("a","BB","CC")));
    $this->assertTrue($ap1->hasOneOfArguments(array("AA","b","CC")));
    $this->assertTrue($ap1->hasOneOfArguments("AA","BB","c"));    
    $this->assertFalse($ap1->hasOneOfArguments(array("AA","BB","CC")));
    $this->assertFalse($ap1->hasOneOfArguments(array()));
    $this->assertFalse($ap1->hasOneOfArguments(array(false,null)));
    $this->assertFalse($ap1->hasOneOfArguments(false,false));
    
  }
  
  public function testGetArgument()
  {
    $ap1 = $this->getArgParser("hasOperands");
    $ap1->parse();
    $this->assertEquals('Argument', get_class($ap1->getArgument('a')));
    $this->assertTrue($ap1->getArgument('a')->getValue());
    $this->assertEquals('value1', $ap1->getArgument('test')->getValue());
    $this->assertEquals("123", $ap1->getArgument('D')->getValue());
    $this->assertFalse($ap1->getArgument(''));
    $this->assertFalse($ap1->getArgument(null));
    $this->assertFalse($ap1->getArgument(false));
  }
  
  public function testGetArgumentValue()
  {
    $ap1 = $this->getArgParser("hasOperands");
    $ap1->parse();
    $this->assertTrue($ap1->getArgumentValue('a'));
    $this->assertEquals('value1', $ap1->getArgumentValue('test'));
    $this->assertFalse($ap1->getArgumentValue(''));
    $this->assertFalse($ap1->getArgumentValue(null));
    $this->assertFalse($ap1->getArgumentValue(false));
  }  
  
  public function testGetArgumentValueIfExists()
  {
    $ap1 = $this->getArgParser("hasOperands");
    $ap1->parse();
    $this->assertTrue($ap1->getArgumentValueIfExists('a', false));
    $this->assertTrue($ap1->getArgumentValueIfExists('b', false));
    $this->assertFalse($ap1->getArgumentValueIfExists('z', false));
    $this->assertEquals(null, $ap1->getArgumentValueIfExists('z', null));
    $this->assertEquals("TEST", $ap1->getArgumentValueIfExists('x', "TEST"));
  }
  
  public function testGetArgumentCount()
  {
    $ap1 = $this->getArgParser("hasOperands");
    $ap2 = $this->getArgParser("noOperands");
    $ap3 = new ArgumentParser(array("./app"));
    
    $ap1->parse();
    $ap2->parse();
    $ap3->parse();
    
    $this->assertEquals(7, $ap1->getArgumentCount());
    $this->assertEquals(5, $ap2->getArgumentCount());
    $this->assertEquals(0, $ap3->getArgumentCount());
  }  
    
}