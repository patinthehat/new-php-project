
<?php

  function new_php_project_autoloader($className)
  {
    if (file_exists(dirname(__FILE__)."/classes/$className.php"))
      include(dirname(__FILE__)."/classes/$className.php"); 
  }
        
  spl_autoload_register('new_php_project_autoloader');
    
  