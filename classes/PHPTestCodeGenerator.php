<?php

class PHPTestCodeGenerator implements ICodeGenerator
{

  public static function generate($project = null, $param = null) 
  {
    $sourceFile = "classes/$param.php";
    $funcs = array();
    
    if (file_exists($sourceFile)) {
      $data = file_get_contents($sourceFile);
      preg_match_all('/function ([_a-zA-Z0-9]*).*\(/', $data, $m);
      foreach($m as $fn) {
        $funcs[] = ucfirst(trim($fn));
      }
    }
    
    $funcCode = "";
    foreach($funcs as $f) {
      $funcCode .= "  public function {$f}Test()\n{\n\n}\n";
    }
    
    $code = "<?php
      
class {$param}Test extends \PHPUnit_Framework_TestCase
{

$funcCode
}
";
    return $code;
  }

}
