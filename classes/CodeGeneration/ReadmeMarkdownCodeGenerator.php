<?php

namespace NPP\CodeGeneration;

class ReadmeMarkdownCodeGenerator implements ICodeGenerator
{

  public static function generate($project = null, $param = null) 
  {
    $projectName = $project->getName();
    $code = "## $projectName ##
---
      
Project description here.
      
---

### Usage ###
---

      
### License ###
---

$projectName is available under the <a href=\"LICENSE\">LICENSE_NAME License</a>.

";
    return $code;
  }

}