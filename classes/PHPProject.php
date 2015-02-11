<?php

/**
 * PHP Project class, implementing PHP-specific project methods
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


class PHPProject extends Project
{
  protected $classes = array();
  
  function setClasses($classes)
  {
    if (is_array($classes) && count($classes)>=0)      
      $this->classes = $classes;
  }
  
  function getClasses() 
  {
    return $this->classes;
  }
  
  function addClass($className)
  {
    $this->classes[] = $className;
  }
  
  function getClassFilenames()
  {
    $ret = array();
    foreach($this->getClasses() as $className) {
      if (strlen(trim($className)) > 0)
        $ret[$className] = "classes/$className.php";
    }
    return $ret;
  }
  
  function addClassFiles()
  {
    $files = $this->getClassFilenames();
    foreach($files as $cn=>$cfn) {
      $this->addFile(new File($cfn, ".", $this->generate_class_code($cn)));
    }
  }
  
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
    return $this->getTargetPath() . "/" . $this->name . ".php";
  }

  function generate_autoloader_code()
  {
    $projectName = $this->getName();
    $projectNameFixed = php_compat_str("${projectName}");
  
    $code = "<?php

  function _${projectNameFixed}_autoloader(\$className)
  {
    if (file_exists(\"classes/\$className.php\"))
      include_once(\"classes/\$className.php\"); 
  }
        
  spl_autoload_register('_${projectNameFixed}_autoloader');
    
  ";
  
    return $code;
  }
  
  function generate_class_code($className)
  {
    $code = "<?php

class $className
{
  function __construct() {
    //
  }

}
";
    return $code;
  }  
  
  function generate_project_code()
  {
    $code = "#!/usr/bin/php
<?php
  
include(\"autoload.php\");
  
";
    return $code;
  }  
}