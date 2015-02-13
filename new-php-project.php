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


require_once(dirname(__FILE__).'/autoload.php');
require_once(dirname(__FILE__).'/include/utils.php');

$ap = new ArgumentParser($argv);
$ap->parse();

if ($ap->hasOneOfArguments("help","h")) {
  usage(basename(__FILE__,".php"));
  die(0);
}

if ($ap->getOperandCount() == 0) {
  usage(basename(__FILE__,".php"));
  die(1);
}

$projectName      = trim($ap->getOperand(0));
$targetBasePath   = realpath(".");
$targetPath       = "$targetBasePath/$projectName"; 

if (project_exists($projectName)) {
  echo "Invalid project name: project already exists.\n";
  die(1);
}

if (!valid_project_name($projectName)) {
  echo "Invalid project name.\n";
  die(1);
}

$pathsArgValue          = $ap->getArgumentValueIfExists("paths",    "");
$classesArgValue        = $ap->getArgumentValueIfExists("classes",  "");
$generateReadme         = $ap->hasOneOfArguments(array("readme",    "R"));
$generateTests          = $ap->hasOneOfArguments(array("tests",     "T"));
$generatePhpUnitConfig  = $ap->hasOneOfArguments(array("phpunit",   "U"));
$codeCoverage           = $ap->hasOneOfArguments(array("coverage",  "C"));
$generateGitIgnore      = $ap->hasOneOfArguments(array("gitignore", "gi"));
$generateLicense        = $ap->hasOneOfArguments(array("license",   "L"));

$gitIgnoreData = "";
//by default, have git ignore the php error log
$gitIgnoreData .= "### php error log ###\n".ini_get('error_log')."\n\n";

//generate a .gitignore file using https://www.gitignore.io API if types were passed
//using --gitignore=a,b,c; see https://www.gitignore.io/api/list
if ($generateGitIgnore) {
  $gitIgnoreIoAPI = new GitIgnoreAPI(new HttpClient());
  $gitIgnoreItems = $ap->getArgumentValueIfExists("gitignore", "");
  if (!$gitIgnoreItems)
    $gitIgnoreItems = $ap->getArgumentValueIfExists("gi", "");
  
  if ($gitIgnoreItems != "" && $gitIgnoreItems !== false) {
    $gitIgnoreIoAPI->addItemsStr($gitIgnoreItems);
    $gitIgnoreIoAPI->getHttpClient()->init(10, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10; rv:33.0) Gecko/20100101 Firefox/33.0");
    $gitIgnoreIoAPI->getHttpClient()->setGzipEncoding(true);
    $gitIgnoreData .= $gitIgnoreIoAPI->getGitIgnore();
    unset($gitIgnoreIoAPI);
  }
}

$licenseName = $ap->getArgumentValueIfExists("license", false);
if (!$licenseName)
  $licenseName = $ap->getArgumentValueIfExists("L", false);

$phpUnitCodeCoverage    = ($generatePhpUnitConfig && $codeCoverage ? 1 : 0);

if ($generatePhpUnitConfig) //phpUnitConfig flag implies --tests
  $generateTests = true;

if ($generateTests)
  $pathsArgValue .= ",tests";

$paths    = explode(',', "classes,include,$pathsArgValue");  //paths to create other than classes,include
$classes  = explode(',', $classesArgValue); //classes to generate, comma-seperated

$project = new PHPProject($projectName, $targetBasePath);
$project->setPaths($paths);       //paths to create
$project->setClasses($classes);   //classnames to generate

$files = array(
  new File("autoload.php", ".",     PHPAutoloadCodeGenerator::generate($project)),
  new File("$projectName.php",".",  PHPProjectCodeGenerator::generate($project)),
  ($generateReadme        ? new File("README.md",".", ReadmeMarkdownCodeGenerator::generate($project)) : false),
  ($generatePhpUnitConfig ? new File("phpunit.xml",".", PHPUnitConfigurationCodeGenerator::generate($project, array('coverage'=>$phpUnitCodeCoverage))) : false),
  ($generateGitIgnore     ? new File(".gitignore", ".", $gitIgnoreData) : false),
  ($generateLicense       ? new File("LICENSE", ".", "") : false), 
);

$project->setFiles($files);       //files to create
$project->addClassFiles($generateTests); //generate new class filenames and add them to files 
$project->createProject();        //generate the new project

chmod($project->getProjectFilename(), 0755); //make project file executable
