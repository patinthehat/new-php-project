<?php
  

class JsonConfiguration extends Configuration
{
  
  public function load()
  {
    $json = file_get_contents($this->getFilename());
    $this->setData($json);
    $cfg = json_decode($json, true);
    $this->setSettings($cfg);
  }
  
  public function save()
  {
    $json = json_encode($this->getSettings(), JSON_PRETTY_PRINT);
    file_put_contents($this->getFilename(), $json);
    return true;
  }
  
  public function getSetting($name)
  {
    return (isset($this->getSettings()[$name]) ? $this->getSettings()[$name] : false);
  }
  
  public function hasSetting($name)
  {
    foreach($this->getSettings() as $n=>$v) {
      if ($n == $name) 
        return true;
    }
    return false;
  }
  
  public function setSetting($name, $value)
  {
    $this->settings[$name] = $value;
  }
  
}