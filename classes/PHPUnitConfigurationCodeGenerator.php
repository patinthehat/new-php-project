<?php

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
            <directory suffix=".php">classes</directory>
            <directory suffix=".php">include</directory>
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