<?php


namespace NPP;

class Application
{
  /**
   * 
   * @var string
   */
  protected $name;
  
  /**
   * 
   * @var \NPP\ArgumentParser
   */
  protected $argParser;
  

  function __construct($argumentParser)
  {
    $this->argParser = $argumentParser;
    $args = $this->argParser->getArgs();
    $this->name = basename($args[0], ".php");
  }
  
  public function argParser()
  {
    return $this->argParser;
  }
  
  public function getName()
  {
    return $this->name;
  }
  
  public function getConfigFilename($ext)
  {
    return $this->getName().".$ext";  
  }
  
  public function errorMessage($msg)
  {
    echo sprintf("Error: %s\n", $msg);
  }
  
  public function statusMessage($msg)
  {
    echo sprintf("%s\n", $msg);
  }
  
}