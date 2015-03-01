<?php

class TestableProject extends \NPP\Project
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