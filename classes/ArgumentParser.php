<?php

require_once(dirname(__FILE__).'/../include/utils.php');

class ArgumentParser 
{
  protected $args;
  protected $flags;
  protected $values;
  protected $arguments = array();
  protected $operands = array();
  
  function __construct($args) 
  {
    if (is_string($args)) {
      $parts = explode(" ", $args);
      $args = $parts;
    }
    
    if (!is_array($args))
      throw new Exception("First argument must be array");
      
    $this->args = $args;
  }
  
  function getOperandCount()
  {
    return count($this->operands);
  }
  
  function getOperand($index)
  {
    if (!is_int($index) || !isset($this->operands[$index]))
      return false;
    return $this->operands[$index];
  }
  
  function addArgument(Argument $arg)
  {
    $this->arguments[] = $arg;
  }
  
  function hasArgument($name)
  {
    foreach($this->arguments as $arg) {
      if ($arg->getName() == $name) 
        return true;
    } 
    return false;      
  }
  
  function getArgument($name)
  {
    foreach($this->arguments as $arg) {
      if ($arg->getName() == $name)
        return $arg;
    }
    return false;
  }
  
  function getArgumentValue($name)
  {
    foreach($this->arguments as $arg) {
      if ($arg->getName() == $name)
        return $arg->getValue();
    }
    return false;
  }
  
  function getArgumentCount()
  {
    return count($this->arguments);
  }
  
  function parse()
  {
    $n = 0;
    foreach($this->args as &$arg) {      
      $c1 = firstChars($arg, 1);
      $c2 = firstChars($arg, 2);
      $argName = "";
      $argValue = "";
      $addOperand = ($n > 0 ? true : false);  //skip the first argument, as it is the program name
      
      if ($c2 == "--") {
        $argName = substr($arg, 2);
        $addOperand = false;
      } else {
        if ($c1 == "-") {
          $argName = substr($arg, 1);
          $argValue = true;
          $argN = "";
          $sLen = strlen($argName);
          //if -a=test then get the value given
          if (strpos($argName, '=')) {
            $parts = explode('=', $argName);
            $argValue = $parts[1];
            $argName = $parts[0];
            $sLen = 1;
          }
          //split -abc to a,b,c and add to this.arguments
          for($i = 0; $i < $sLen; $i++) {
            $argN = $argName[$i];
            $a = new Argument($argN, $argValue);
            $this->addArgument($a);
          }
          $argName = "";
          $addOperand = false;
        }
      }
      
      //extract value given
      if ($eqPos = strpos($argName, "=")) {
        $parts = explode("=", $argName);
        $argName = $parts[0];
        $argValue = $parts[1];
        $addOperand = false;
      }
      
      if ($argName !== "") {
        $a = new Argument($argName, $argValue);
        $this->addArgument($a);
      }
      
      if ($addOperand)
        $this->operands[] = $arg;
      
      $n++;
    }
  }
  
  
}