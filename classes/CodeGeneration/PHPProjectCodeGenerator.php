<?php

namespace NPP\CodeGeneration;

class PHPProjectCodeGenerator implements ICodeGenerator
{

  public static function generate($project = null, $param = null) 
  {
    $hashbang = "";
    if (isset($param['exec']) && $param['exec']==true)
      $hashbang = "#!/usr/bin/php\n";
    
    $code = "$hashbang<?php
  
include(\"autoload.php\");
  
";
    return $code;
  }

}