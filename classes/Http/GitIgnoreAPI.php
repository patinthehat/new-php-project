<?php

namespace NPP\Http;

class GitIgnoreAPI
{
  protected $http;
  protected $items = array();
  
  function __construct(HttpClient $http)
  {
    $this->http = $http;
    $this->resetItems();
  }
  
  public function getHttpClient()
  {
    return $this->http;
  }
  
  protected function baseUrl()
  {
    return "https://www.gitignore.io/api";
  }
  
  protected function apiUrl($endPoint)
  {
    if (strlen(trim($endPoint)) == 0)
      return false;

    return sprintf("%s/%s", $this->baseUrl(), $endPoint);
  }
  
  public function resetItems() 
  {
    $this->items = array();
  }
  
  public function addItem($itemName)
  {
    $itemName = trim($itemName);
    if (is_string($itemName) && strlen($itemName) > 0)
      if (!$this->hasItem($itemName)) {
        $this->items[] = $itemName;
        return true;
      }
    return false;
  }
  
  public function addItems(array $items)
  {
    foreach($items as $item)
      $this->addItem($item);
  }
  
  public function addItemsStr($itemsStr)
  {
    $parts = explode(",", $itemsStr);
    sort($parts);
    $this->addItems($parts);
  }
  
  public function hasItem($itemName)
  {
    foreach($this->items as $item) {
      if ($itemName == $item)
        return true;
    }
    return false;
  }
  
  public function getItems()
  {
    return $this->items;
  }
  
  public function getItemList()
  {
    sort($this->items);
    return implode(",", $this->getItems());
  }
  
  public function getList($returnRaw = false) 
  {
    $list = $this->http->get($this->apiUrl("list"));
    $ret = ($returnRaw ? $list : explode(",", $list));
    return $ret;
  }
  
  public function getCached()
  {
    $data = $this->http->get($this->apiUrl("_cached_"));
    return $data;
  }

  public function getGitIgnore()
  {
    if (count($this->items) == 0)
      return false;
    
    sort($this->items);
    $endPoint = $this->getItemList();
    if ($endPoint == "")
      return false;
    
    $data = $this->http->get($this->apiUrl($endPoint));
    //ensure the http request actually succeeded
    if (!$this->http->hasSuccessCode())
      return false;
    
    return $data;
  }

}
