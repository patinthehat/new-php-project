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
Usage: $projectFilename [project-name] [paths-to-create] [classes-to-create]
i.e., $projectFilename 'myproject' 'tests' 'MyClass1,MyClass2,MyClass3'
i.e., $projectFilename 'myproject' '' ''
";

  echo trim($message) . PHP_EOL;
}

function valid_project_name($projectName)
{
  $ret = TRUE;
  if (file_exists($projectName))
    return FALSE;

  return $ret;
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