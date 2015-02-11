<?php

class TestableProject extends Project
{
  
  function preCreateProject()
  {
    return TRUE;
  }
  
  function postCreateProject()
  {
    return TRUE;
  }
  
  function getProjectFilename()
  {
    return $this->getTargetPath() . "/" . $this->name;
  }
  

}