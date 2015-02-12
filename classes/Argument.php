<?php

class Argument
{
  protected $name;
  protected $value;
  
  function __construct($name, $value)
  {
    $this->name = $name;
    $this->value = $value;
  }
  
  function getName()
  {
    return $this->name;
  }
  
  function getValue()
  {
    return $this->value;
  }
  
  function setValue($value)
  {
    $this->value = $value;
  }  
  
}