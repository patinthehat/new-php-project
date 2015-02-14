<?php
  
namespace NPP\CodeGeneration;

interface ICodeGenerator
{
  public static function generate($project = NULL, $param = NULL);
  
}