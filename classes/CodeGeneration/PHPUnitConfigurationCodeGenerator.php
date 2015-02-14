<?php

namespace NPP\CodeGeneration;

class PHPUnitConfigurationCodeGenerator implements ICodeGenerator
{

  public static function generate($project = null, $param = null) 
  {
    $opts = (is_array($param) ? $param : array() );
    $generateCoverage = (isset($opts['coverage']) && $opts['coverage']==1);
    $coverageCode = ($generateCoverage ? '
      <log type="coverage-html"
            target="build/coverage"
            charset="UTF-8"
            yui="true"
            highlight="true"
            lowUpperBound="40"
            highLowerBound="70"
        />' : "");
    $dirs = "";

    if (!is_null($project)) {
      foreach($project->getPaths() as $path)
        if ($path != "tests")
          $dirs .= "            <directory suffix=\".php\">$path</directory>\n";
    }
    
    if ($dirs == "")
      $dirs = "            <directory suffix=\".php\">classes</directory>";
    
    $code = '
<?xml version="1.0" encoding="UTF-8"?>

<phpunit 
    bootstrap="autoload.php"
    colors="true"
    convertErrorsToExceptions="true"
    convertNoticesToExceptions="true"
    convertWarningsToExceptions="true"
    stopOnFailure="true"
    syntaxCheck="true"
>
    <testsuites>
        <testsuite name="basic">
            <directory>tests</directory>
        </testsuite>
    </testsuites>

    <filter>
        <whitelist>
'.$dirs.'
        </whitelist>
    </filter>

    <logging>
'.$coverageCode.'
    </logging>

</phpunit>  
';
    return trim($code);
  }

}