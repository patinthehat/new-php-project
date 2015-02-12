<?php

class PHPTestCodeGenerator implements ICodeGenerator
{

  public static function generate($project = null, $param = null) 
  {
    $sourceFile = "$param";
    $funcs = array();
    $className = "";
    
    if (file_exists($sourceFile)) {
      $data = file_get_contents($sourceFile);
      preg_match_all('/function ([_a-zA-Z0-9]*).*\(/', $data, $m);
      foreach($m[1] as $fn) {
        if (firstChars($fn, 2)=="__") //skip magic methods
          continue;
        $funcs[] = ucfirst(trim($fn));
      }
    }
    
    $funcCode = "";
    foreach($funcs as $f) {
      $funcCode .= "  public function test{$f}()\n  {\n\n  }\n\n";
    }
    
    if (preg_match('/.*\/(.*)\.php/', $param, $m) == 1)
      $className = $m[1];
    
    $code = "<?php
      
class {$className}Test extends \PHPUnit_Framework_TestCase
{

$funcCode
}
";
    return $code;
  }

}
