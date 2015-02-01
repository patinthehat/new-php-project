#!/usr/bin/php
<?php
/**
 * Creates a new PHP project in the current directory.
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


include('autoload.php');
include_once('include/utils.php');

if ($argc < 4) {
  usage(basename(__FILE__,".php"));
  die(1);
}

$projectName      = trim($argv[1]);
$targetBasePath   = realpath(".");
$targetPath       = "$targetBasePath/$projectName"; 

if (!valid_project_name($projectName)) {
  echo "Invalid project name: project already exists.";
  die(1);
}

$paths    = explode(',', "classes,include,".trim($argv[2]));  //paths to create other than classes,include
$classes  = explode(',', trim($argv[3])); //classes to generate, comma-seperated

$project = new PHPProject($projectName, $targetBasePath);

$files = array(
  "autoload.php"=>$project->generate_autoloader_code(),
  "$projectName.php"=>$project->generate_project_code(),
  "README.md"=>"",
);

$project->setClasses($classes);   //classnames to generate
$project->setPaths($paths);       //paths to create
$project->setFiles($files);       //files to create
$project->addClassFiles();        //generate new class filenames and add the to files
$project->createProject();        //generate the new project

chmod($project->getProjectFilename(), 0755); //make project file executable