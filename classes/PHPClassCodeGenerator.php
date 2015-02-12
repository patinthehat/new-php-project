<?php

class PHPClassCodeGenerator implements ICodeGenerator
{

  public static function generate($project = null, $param = null) 
  {
    $code = "<?php

class $param
{

  function __construct() {
    //
  }

}
";
    return $code;
  }

}