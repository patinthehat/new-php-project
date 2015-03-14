#!/usr/bin/php
<?php
/**
 * @author Patrick Organ <trick.developer@gmail.com>
 * @version 0.17
 * @license MIT
 * 
 * Creates a new PHP project in the current directory.
 *
 * beta version.
 * 
 * Uses Travis CI and hipchat for notifications.
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

$app = new \NPP\Application(new \NPP\ArgumentParser($argv));


$applicationName  = $app->getName();
//@todo convert functional calls to application/class calls

if (!configuration_file_exists()) {
  if (configuration_dist_file_exists()) {
    $app->errorMessage("Configuration file does not exist.  Please rename the '$applicationName.json.dist' file.");
  } else {
    $app->errorMessage("Configuration file '$applicationName.json' not found.");
  }
  die(1);
}

$app->argParser()->parse();

$config = new \JsonConfiguration(realpath(dirname(__FILE__))."/".$app->getConfigFilename('json'));
$config->load();
$config->setSetting("year", date('Y'));

//used by LicenseTemplate to get variable values and to format them
$getVariable = 
  function($name) use ($config) {
    $ret = $config->getSetting($name);
    if ($name=="email") //wrap email address in "< .. >"
      $ret = "<$ret>";
    return $ret;
  };
  

if ($app->argParser()->hasOneOfArguments("help","h")) {
  usage(basename(__FILE__,".php"));
  die(0);
}

if ($app->argParser()->getOperandCount() == 0) {
  usage(basename(__FILE__,".php"));
  die(1);
}

$projectName      = trim($app->argParser()->getOperand(0));
$targetBasePath   = realpath(".");
$targetPath       = "$targetBasePath/$projectName"; 

if (project_exists($projectName)) {
  $app->errorMessage("Invalid project name: project already exists.");
  die(1);
}

if (!valid_project_name($projectName)) {
  $app->errorMessage("Invalid project name.");
  die(1);
}

$pathsArgValue          = $app->argParser()->getArgumentValueIfExists("paths",    "");
$classesArgValue        = $app->argParser()->getArgumentValueIfExists("classes",  "");
$generateReadme         = $app->argParser()->hasOneOfArguments(array("readme",    "R"));
$generateTests          = $app->argParser()->hasOneOfArguments(array("tests",     "T"));
$generatePhpUnitConfig  = $app->argParser()->hasOneOfArguments(array("phpunit",   "U"));
$codeCoverage           = $app->argParser()->hasOneOfArguments(array("coverage",  "C"));
$generateGitIgnore      = $app->argParser()->hasOneOfArguments(array("gitignore", "gi"));
$generateLicense        = $app->argParser()->hasOneOfArguments(array("license",   "L"));
$makeProjectFileExec    = $app->argParser()->hasOneOfArguments(array("exec",      "X"));
$generateWebProject     = $app->argParser()->hasOneOfArguments(array("web",       "W"));

$gitIgnoreData = "";

//generate a .gitignore file using https://www.gitignore.io API if types were passed
//using --gitignore=a,b,c; see https://www.gitignore.io/api/list
if ($generateGitIgnore) {
  //by default, have git ignore the php error log
  $gitIgnoreData .= "### php error log ###\n".ini_get('error_log')."\n\n";
  
  $gitIgnoreIoAPI = new \NPP\Http\GitIgnoreAPI(new \NPP\Http\HttpClient());
  $gitIgnoreItems = $app->argParser()->getArgumentValueIfExists("gitignore", "");
  if (!$gitIgnoreItems || $gitIgnoreItems == "")
    $gitIgnoreItems = $app->argParser()->getArgumentValueIfExists("gi", "");
  
  if ($gitIgnoreItems != "" && $gitIgnoreItems !== false) {
    $gitIgnoreIoAPI->addItemsStr($gitIgnoreItems);
    $gitIgnoreIoAPI->getHttpClient()->init(10, $config->getSetting("user-agent"));
    $gitIgnoreIoAPI->getHttpClient()->setGzipEncoding(true);
    $gitIgnoreData .= $gitIgnoreIoAPI->getGitIgnore();
  }
}

$licenseName = $app->argParser()->getArgumentValueIfExists("license", false);
if (!$licenseName)
  $licenseName = $app->argParser()->getArgumentValueIfExists("L", false);

if ($app->argParser()->hasOneOfArguments(array("license","L"))) {
  $lt = \NPP\LicenseTemplate::create(realpath(dirname(__FILE__))."/data/licenses/$licenseName.xml");
  $lt->processNoticeVariables($getVariable);
  $lt->processLicenseVariables($getVariable);
  //wrap notice in a multi-line comment
  $lt->notice = "/**\n".str_replace("\n", "\n * ", " * ".trim($lt->notice))."\n*/\n";
} else {
  $lt = \NPP\LicenseTemplate::create(false); //creates an empty LicenseTemplate object
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
  $webPaths = $config->getSetting("default-paths-web");
  $webPathParts = explode(',', $webPaths);
  foreach($webPathParts as $wpp)
    if (trim($wpp)!='')
      $paths[] = trim($wpp);
}

$project = new \NPP\PHPProject($projectName, $targetBasePath);
$project->setPaths($paths);       //paths to create
$project->setClasses($classes);   //classnames to generate

$files = array(
  new \NPP\File("autoload.php", ".",     \NPP\CodeGeneration\PHPAutoloadCodeGenerator::generate($project)),
  new \NPP\File("$projectName.php",".",  \NPP\CodeGeneration\PHPProjectCodeGenerator::generate($project, array('exec'=>$makeProjectFileExec))),
  ($generateReadme        ? new \NPP\File("README.md",".", \NPP\CodeGeneration\ReadmeMarkdownCodeGenerator::generate($project)) : false),
  ($generatePhpUnitConfig ? new \NPP\File("phpunit.xml",".", \NPP\CodeGeneration\PHPUnitConfigurationCodeGenerator::generate($project, array('coverage'=>$phpUnitCodeCoverage))) : false),
  ($generateGitIgnore     ? new \NPP\File(".gitignore", ".", $gitIgnoreData) : false),
  ($generateLicense       ? new \NPP\File("LICENSE", ".",    $licenseData) : false),
  ($generateWebProject    ? new \NPP\File("index.php", ".",  "<?php\n\n") : false),
);

//default files to generate for a web project
if ($generateWebProject) {
  $defaultWebFiles = explode(',', $config->getSetting('default-files-web'));
  foreach($defaultWebFiles as $dwf) 
    $files[] = new \NPP\File($dwf, ".",  "");
}

$project->setFiles($files);       //files to create
$project->addClassFiles($generateTests); //generate new class filenames and add them to files 
$project->createProject();        //generate the new project

if ($makeProjectFileExec)
  chmod($project->getProjectFilename(), 0755); //make project file executable
