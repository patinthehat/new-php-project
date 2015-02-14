<?php

namespace NPP\CodeGeneration;

class PHPAutoloadCodeGenerator implements ICodeGenerator
{
 
  public static function generate($project= null, $param = null) 
  {
    $projectName = $project->getName();
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

}