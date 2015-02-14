<?php

abstract class Configuration
{
    protected $filename;
    protected $data;
    protected $settings = array();

    public function getFilename()
    {
      return $this->filename;
    }

    protected function setFilename($value)
    {
      $this->filename = $value;
    }

    public function getData()
    {
      return $this->data;
    }

    protected function setData($value)
    {
      $this->data = $value;
    }
    
    protected function setSettings($value)
    {
      $this->settings = $value;
    }
    
    public function getSettings()
    {
      return $this->settings;
    }

    public function init($filename)
    {
      $this->settings = array();
      $this->setFilename($filename);
    }

    abstract function load();
    abstract function save();

    abstract function getSetting($name);
    abstract function hasSetting($name);
    abstract function setSetting($name, $value);  
}
