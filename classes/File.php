<?php
  
class File 
{ 
  protected $path;
  protected $filename;
  protected $data;
  
  public function __construct($filename, $path = ".", $data = NULL) {
    $this->filename = $filename;
    $this->path = $path;
    $this->data = $data;
  }
  
  function getPath() {
    return $this->path;
  }
  
  function setPath($path) {
    $this->path = $path;    
  }
  
  function getFilename() {
    return $this->filename;
  }
  
  function getData() {
    return $this->data;
  }
  
  function load() {
    if (!$this->exists())
      return FALSE;
    $this->data = file_get_contents($this->getPath() . "/" .$this->getFilename());
    return $this->data;
  }
  
  function save() {
    return file_put_contents($this->getPath() . "/" .$this->getFilename(), $this->data);
  }
  
  function exists() {
    return file_exists($this->getPath() . "/" . $this->getFilename());
  }
  
  function unlink() {
    if (!$this->exists())
      return TRUE;
    return unlink($this->getFilename());
  }
  
}