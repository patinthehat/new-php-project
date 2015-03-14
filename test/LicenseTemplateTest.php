<?php


class LicenseTemplateTest extends \PHPUnit_Framework_TestCase
{
  protected $tempFile;
  
  public function setUp() 
  {
    $this->tempFile = "TESTLICENSETEMPLATE".mt_rand(999, 999999).".xml";
      $data = "
<?xml version=\"1.0\"?>
<licenseTemplate>
  <name>TEST</name>
  <version>1.0</version>
  <notice>TEST $[author] TEST</notice>
  <license>TEST $[author] TEST</license>
</licenseTemplate>";
      
    file_put_contents($this->tempFile, $data);
  }
  
  public function tearDown()
  {
    if (file_exists($this->tempFile))
      unlink($this->tempFile);
  }
  
  public function testCreateGoodAndBadFiles() 
  {
    $lt = \NPP\LicenseTemplate::create($this->tempFile);
    $this->assertTrue(is_a($lt, 'NPP\\LicenseTemplate'));
    $lt = \NPP\LicenseTemplate::create("BADFILE.EXT");
    $this->assertTrue(is_a($lt, 'NPP\\LicenseTemplate'));
  }

  public function testProcessVariables()
  {
    $getVariable =
      function($name) {
        $ret = strtoupper($name);
        return $ret;
      };
        
    $lt = \NPP\LicenseTemplate::create($this->tempFile);
    $lt->processNoticeVariables($getVariable);
    $this->assertRegExp('/AUTHOR/', $lt->getNotice());
    $lt->processLicenseVariables($getVariable);
    $this->assertRegExp('/AUTHOR/', $lt->getLicense());    
  }
  
}
