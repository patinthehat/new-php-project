<?php
/**
 * Misc utility functions
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

function usage($projectFilename)
{
  $message = "$projectFilename - generate a new PHP project.
Usage: 
   $projectFilename <options> [project-name]

   --paths=[a,b,...]    : directories to create in the project folder, comma separated.
   --classes=[a,b,...]  : classes to generate, comma separated.
   --gi|--gitignore     : generate an empty .gitignore file.
   --gitignore=[a,b,...]: generate a .gitignore file using the gitignore.io api; see http://gitignore.io/api/list   
   -L|--license         : generate an empty LICENSE file.
   -R|--readme          : generate a readme file in markdown format.
   -T|--tests           : generate a \"tests\" directory and unit tests for generated classes.
   -U|--phpunit         : generate a PHPUnit configuration file. Implies --tests.
   -C|--coverage        : generate code coverage report, requires --phpunit.
   -X|--exec            : add a hashbang line to the project.php file, and chmod +x project.php.
   -W|--web             : generate a website project; creates additonal default paths and files.
   -h|--help            : show this message.

Examples:
  * $projectFilename 'myProjectName' --paths=templates --classes=MyClass1,MyClass2 --readme
  * $projectFilename 'myProjectName' --tests
  * $projectFilename -TR myProjectName

";

  echo trim($message) . PHP_EOL;
}

function valid_project_name($projectName)
{
  $ret = TRUE;
  $projectName = safe_filesystem_name($projectName);
  if (file_exists($projectName) || $projectName == "")
    return FALSE;

  return $ret;
}

function project_exists($projectName)
{
  if (file_exists($projectName) && is_dir($projectName))
    return true;
  return false;
}

function configuration_file_exists()
{
  return (file_exists(dirname(__FILE__)."/../new-php-project.json"));
}

function configuration_dist_file_exists()
{
  return (file_exists(dirname(__FILE__)."/../new-php-project.json.dist"));
}

function dashes_to_underscores($str)
{
  $str = str_replace("-", "_", $str);
  return $str;
}

function spaces_to_underscores($str)
{
  $str = str_replace(" ", "_", $str);
  return $str;
}

function php_compat_str($str)
{
  $str = trim($str);
  
  $str = spaces_to_underscores($str);
  $str = dashes_to_underscores($str);
  return $str;  
}

function safe_filesystem_name($str)
{
  $str = preg_replace('/:/', '', $str);
  $str = preg_replace('/\$/', '', $str);
  $str = preg_replace('/&/', '', $str);
  return $str;
}

function firstChars($str, $count)
{
  if (!is_string($str))
    return "";
  return substr($str, 0, $count);
}