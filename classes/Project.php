<?php
/**
 * Base Project class providing basic project functionality
 *
 * =============================================================================
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Patrick Organ - permafrostcodingstudio.com <trick.developer@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 */

namespace NPP;

abstract class Project
{
  protected $name;
  protected $basePath = "";
  protected $targetPath = "";
  protected $files = array();
  protected $paths = array();
  
  function init($name, $basePath)
  {
    $this->setName($name);
    $this->setBasePath($basePath);    
  }
  
  function __construct($name, $basePath)
  {
    $this->init($name, $basePath);
    
  }  
    
  function getName()
  {
    return $this->name;
  }
  
  function setName($value)
  {
    $this->name = $value;
  }
  
  function getBasePath()
  {
    return $this->basePath;
  }
  
  function setBasePath($value)
  {
    $this->basePath = $value;
    $this->targetPath = "$value/".$this->name;
  }

  function getTargetPath()
  {
    return $this->targetPath;
  }
    
  function addPath($path) 
  {
    $this->paths[] = $path;
  }
  
  function setPaths($paths)
  {
    $this->paths = $paths;
  }
  
  function getPaths() 
  {
    return $this->paths;
  }
  
  function addFile($file)
  {
    $file->setPath($this->getTargetPath());
    $this->files[$file->getFilename()] = $file;
  }  
  
  function setFiles($files)
  {
    $this->files = array();
    foreach($files as $f) {
      if ($f)
        $this->files[$f->getFilename()] = $f;
    }
  }
  
  function getFiles()
  {
    return $this->files;
  }
  
  function createProjectPaths() 
  {
    $targetPath = $this->getTargetPath();
    
    if (!file_exists($targetPath))
      mkdir("$targetPath");
    
    foreach($this->paths as $path){
      //echo "createPath: $targetPath/$path\n";
      if (!file_exists("$targetPath/$path"))
        mkdir("$targetPath/$path");
    }
    
    return TRUE;
  }
  
  function createProjectFiles()
  {
    $targetPath = $this->getTargetPath();
    foreach($this->files as $filename=>$f){
      $f->setPath($targetPath);
      //echo "createFile: $targetPath/$file\n";
      if (!$f->exists())
        $f->save();//file_put_contents("$targetPath/$file", $data);
    }
    
    return TRUE;
  }
  
  function createProject()
  {
    if (!$this->preCreateProject())
      return FALSE;
    $ret = TRUE;
    $ret = $ret && $this->createProjectPaths();
    $ret = $ret && $this->createProjectFiles();
    $this->postCreateProject();
    return $ret;
  }
  

  abstract function preCreateProject();
  abstract function postCreateProject();
  abstract function getProjectFilename();
}