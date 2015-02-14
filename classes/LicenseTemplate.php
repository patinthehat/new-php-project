<?php

class LicenseTemplate extends SimpleXMLElement 
{
  
  public static function create($filename)
  {
    if (file_exists($filename)) {
      $data = file_get_contents($filename);
    } else {
      if ($filename !== false)
        echo "License file not found.\n";

      $data = "
<?xml version=\"1.0\"?>
<licenseTemplate>
  <name>Invalid License Template</name>
  <version></version>
  <notice></notice>
  <license></license>
</licenseTemplate>";
    }
    
    $o = new self(trim($data));//$options, $data_is_url, $ns, $is_prefix)
    return $o;
  }
  
  public function processNoticeVariables($getVariableCallback)
  {
    $re = '/(\$\[([a-zA-Z0-9_\-]{1,})\])/';
    $this->notice = preg_replace_callback($re,
      function($matches) use ($getVariableCallback)  {
        $ret = call_user_func_array($getVariableCallback, array($matches[2]));
        return $ret;
      },
      $this->notice);
    $this->notice = html_entity_decode($this->notice);
  }

  public function processLicenseVariables($getVariableCallback)
  {
    $re = '/(\$\[([a-zA-Z0-9_\-]{1,})\])/';
    $this->license = preg_replace_callback($re, 
        function($matches) use ($getVariableCallback)  {
          $ret = call_user_func_array($getVariableCallback, array($matches[2]));
          return $ret;
        }, 
        $this->license);
    $this->license = html_entity_decode($this->license);
  }
}