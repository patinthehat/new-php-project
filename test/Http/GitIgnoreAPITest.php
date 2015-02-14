<?php
      
class GitIgnoreAPITest extends \PHPUnit_Framework_TestCase
{
  protected $http;
  protected $giapi;
  
  function setUp()
  {
    $this->http = new \NPP\Http\HttpClientStub(array(
        '/\/api\/list/'=>array(
              'name'=>'getList', 
              'code'=>200, 
              'data'=>'eclipse,linux'
          
        ),
        '/\/api\/([a-z,]{1,})$/'=>array(
              'name'=>'getGitIgnore',
              'code'=>200, 
              'data'=>"# Created by https://www.gitignore.io\n# TEST RESPONSE\n\n".
                      "### Linux ###\n*~\n\n".
                      "### Eclipse ###\n.project\n.buildpath\n\n".
                      "",
        ),
        '/\/api\/_cached_$/'=>array(
              'name'=>'getList', 
              'code'=>304, 
              'data'=>''
        ),
      )
    );    
    $this->giapi = new \NPP\Http\GitIgnoreAPI($this->http);
  }

  public function testAddItem()
  {
    $this->giapi->resetItems();
    $this->assertCount(0, $this->giapi->getItems());
    $this->assertTrue($this->giapi->addItem("eclipse"));
    $this->assertCount(1, $this->giapi->getItems());
    $this->assertFalse($this->giapi->addItem(""));
    $this->assertCount(1, $this->giapi->getItems());
  }
  
  public function testAddItems()
  {
    $this->giapi->resetItems();
    $this->assertCount(0, $this->giapi->getItems());
    $this->giapi->addItems(array("eclipse","linux"));
    $this->assertCount(2, $this->giapi->getItems());
    $this->assertTrue($this->giapi->hasItem('linux'));
  }

  public function testAddItemsStr()
  {
    $this->giapi->resetItems();
    $this->assertCount(0, $this->giapi->getItems());
    $this->giapi->addItemsStr("eclipse,linux");
    $this->assertCount(2, $this->giapi->getItems());
    $this->assertTrue($this->giapi->hasItem('linux'));
  }
  
  public function testHasItem()
  {
    $this->giapi->resetItems();
    $this->giapi->addItems(array("eclipse","linux"));
    $this->assertTrue($this->giapi->hasItem("eclipse"));
    $this->assertTrue($this->giapi->hasItem("linux"));
    $this->assertFalse($this->giapi->hasItem("ABC"));
    $this->assertFalse($this->giapi->hasItem(false));
  }
  
  public function testGetItemList()
  {
    $this->giapi->resetItems();
    $this->giapi->addItems(array("eclipse","linux"));
    $this->assertEquals("eclipse,linux", $this->giapi->getItemList());
  }  
  
  public function testGetList()
  {
    $this->giapi->resetItems();
    $a = array('eclipse', 'linux');
    $this->assertEquals("eclipse,linux", $this->giapi->getList(true));
    $this->assertEquals($a, $this->giapi->getList(false));
  }
  
  public function testGetGitIgnore()
  {
    $this->giapi->resetItems();
    $this->giapi->addItems(array("eclipse,linux"));
    $data = $this->giapi->getGitIgnore();
    
    $this->assertRegExp("/gitignore.io/", $data);
    $this->assertRegExp("/### Linux ###/", $data);
    $this->assertRegExp("/### Eclipse ###/", $data);
  }
  
  public function testGet304Response()
  {
    $this->giapi->resetItems();
    $data = $this->giapi->getCached();
    $this->assertRegExp("/eclipse/", $data);
    $this->assertRegExp("/linux/", $data);
  }
  

}
