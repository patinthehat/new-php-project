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
   * @var \ArgumentParser
   */
  protected $argParser;
  
  
  function __construct($argumentParser)
  {
    global $argv;
    $this->name = basename($argv[0], ".php");
    $this->argParser = $argumentParser;
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