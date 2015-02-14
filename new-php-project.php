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

namespace NPP;

require_once(dirname(__FILE__).'/autoload.php');
require_once(dirname(__FILE__).'/include/utils.php');

$applicationName  = basename($argv[0],".php");

if (!configuration_file_exists()) {
  if (configuration_dist_file_exists()) {
    echo "Error: Configuration file does not exist.  Please rename the '$applicationName.json.dist' file.\n";
  } else {
    echo "Error: Configuration file '$applicationName.json' not found.\n";
  }
  die(1);
}

$config = new \JsonConfiguration(basename(__FILE__,".php").".json");
$config->load();
$config->setSetting("year", date('Y'));

$getVariable = 
  function($name) use ($config) {
    $ret = $config->getSetting($name);
    if ($name=="email") //wrap email address in "< .. >"
      $ret = "<$ret>";
    return $ret;
  };
  
$ap = new \ArgumentParser($argv);
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
$makeProjectFileExec    = $ap->hasOneOfArguments(array("exec",      "X"));
$generateWebProject     = $ap->hasOneOfArguments(array("web",       "W"));

$gitIgnoreData = "";

//generate a .gitignore file using https://www.gitignore.io API if types were passed
//using --gitignore=a,b,c; see https://www.gitignore.io/api/list
if ($generateGitIgnore) {
  //by default, have git ignore the php error log
  $gitIgnoreData .= "### php error log ###\n".ini_get('error_log')."\n\n";
  
  $gitIgnoreIoAPI = new \NPP\Http\GitIgnoreAPI(new \NPP\Http\HttpClient());
  $gitIgnoreItems = $ap->getArgumentValueIfExists("gitignore", "");
  if (!$gitIgnoreItems || $gitIgnoreItems == "")
    $gitIgnoreItems = $ap->getArgumentValueIfExists("gi", "");
  
  if ($gitIgnoreItems != "" && $gitIgnoreItems !== false) {
    $gitIgnoreIoAPI->addItemsStr($gitIgnoreItems);
    $gitIgnoreIoAPI->getHttpClient()->init(10, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10; rv:33.0) Gecko/20100101 Firefox/33.0");
    $gitIgnoreIoAPI->getHttpClient()->setGzipEncoding(true);
    $gitIgnoreData .= $gitIgnoreIoAPI->getGitIgnore();
  }
}

$licenseName = $ap->getArgumentValueIfExists("license", false);
if (!$licenseName)
  $licenseName = $ap->getArgumentValueIfExists("L", false);

if ($ap->hasOneOfArguments(array("license","L"))) {
  $lt = \LicenseTemplate::create("data/licenses/$licenseName.xml");
  $lt->processNoticeVariables($getVariable);
  $lt->processLicenseVariables($getVariable);
  //wrap notice in a multi-line comment
  $lt->notice = "/**\n".str_replace("\n", "\n * ", " * ".trim($lt->notice))."\n*/\n";
} else {
  $lt = \LicenseTemplate::create(false); //creates an empty LicenseTemplate object
}
$licenseData = trim($lt->license);


$phpUnitCodeCoverage    = ($generatePhpUnitConfig && $codeCoverage ? 1 : 0);

if ($generatePhpUnitConfig) //phpUnitConfig flag implies --tests
  $generateTests = true;

if ($generateTests)
  $pathsArgValue .= ",".$config->getSetting("tests-path");

$paths    = explode(',', $config->getSetting("default-paths").",$pathsArgValue");  //paths to create other than classes,include
$classes  = explode(',', $classesArgValue); //classes to generate, comma-seperated


if ($generateWebProject) {
  $paths[] = "css";
  $paths[] = "js";
  $paths[] = "images";
}

$project = new \PHPProject($projectName, $targetBasePath);
$project->setPaths($paths);       //paths to create
$project->setClasses($classes);   //classnames to generate

$files = array(
  new \File("autoload.php", ".",     \NPP\CodeGeneration\PHPAutoloadCodeGenerator::generate($project)),
  new \File("$projectName.php",".",  \NPP\CodeGeneration\PHPProjectCodeGenerator::generate($project, array('exec'=>$makeProjectFileExec))),
  ($generateReadme        ? new \File("README.md",".", \NPP\CodeGeneration\ReadmeMarkdownCodeGenerator::generate($project)) : false),
  ($generatePhpUnitConfig ? new \File("phpunit.xml",".", \NPP\CodeGeneration\PHPUnitConfigurationCodeGenerator::generate($project, array('coverage'=>$phpUnitCodeCoverage))) : false),
  ($generateGitIgnore     ? new \File(".gitignore", ".", $gitIgnoreData) : false),
  ($generateLicense       ? new \File("LICENSE", ".",    $licenseData) : false),
  ($generateWebProject    ? new \File("index.php", ".",  "<?php\n\n") : false),
  ($generateWebProject    ? new \File("js/main.js", ".",  "") : false),
  ($generateWebProject    ? new \File("css/main.css", ".",  "") : false),
);

$project->setFiles($files);       //files to create
$project->addClassFiles($generateTests); //generate new class filenames and add them to files 
$project->createProject();        //generate the new project

if ($makeProjectFileExec)
  chmod($project->getProjectFilename(), 0755); //make project file executable
