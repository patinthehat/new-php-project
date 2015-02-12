<?php

class PHPProjectCodeGenerator implements ICodeGenerator
{

  public static function generate($project = null, $param = null) 
  {
    $code = "#!/usr/bin/php
<?php
  
include(\"autoload.php\");
  
";
    return $code;
  }

}