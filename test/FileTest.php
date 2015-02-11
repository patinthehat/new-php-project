<?php


class FileTest extends \PHPUnit_Framework_TestCase
{
  
  public function testInit()
  {
    $f = new File('TESTNAME', '', 'TESTDATA');
    $this->assertEquals('TESTNAME', $f->getFilename());
    $this->assertEquals('TESTDATA', $f->getData());
  }
  
  public function testSave()
  {
    $rand = mt_rand(1000, 9999);
    $fn = "TESTFILE$rand";
    $f = new File($fn, '.', 'TESTDATA');
    $f->save();
    $this->assertFileExists($f->getFilename());
    $this->assertFileExists($fn);
    $this->assertEquals(md5('TESTDATA'), md5_file($f->getFilename()));
    unlink($f->getFilename());
  }  
  
  public function testLoad()
  {
    $f = new File(basename(__FILE__), dirname(__FILE__));
    $this->assertEquals($f->getData(), "");
    $f->load();
    $this->assertEquals(md5($f->getData()), md5_file(__FILE__));
  }

  public function testExists()
  {
    $f = new File(basename(__FILE__), dirname(__FILE__));
    $this->assertTrue($f->exists());
    $this->assertEquals($f->exists(), file_exists(__FILE__));
  }  

  public function testUnlink()
  {
    $rand = mt_rand(1000, 9999);
    $fn = "TESTFILE$rand";
    $f = new File($fn, '.', 'TESTDATA');
    $f->save();
    $this->assertFileExists($f->getFilename());
    $f->unlink();
    $this->assertFileNotExists($f->getFilename());
  }  
  
}