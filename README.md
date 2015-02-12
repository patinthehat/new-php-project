## new-php-project ##
---

Generates a basic PHP project in the current directory.

[![Build Status](https://travis-ci.org/patinthehat/new-php-project.png)](https://travis-ci.org/patinthehat/new-php-project)

---
### Usage ###
---

  `new-php-project.php "project-name"`

  __Optional Flags__:
  
    - `-T|--tests` -  generate a "tests" directory.
    
    - `-R|--readme` - generate a README file.
    
    - `--classes=<a,b,...>` - generate classes, comma separated.
    
    - `--paths=<a,b,...> - generate additional paths, comma separated.
    
  
  __Examples__:
    - `new-php-project.php "project1" --tests --classes=MyClass1,MyClass2` 
    - `new-php-project.php "project1" -RT` 
    - `new-php-project.php "project1" --readme --tests` 
    - `new-php-project.php "project1"`
  
---
### Defaults ###
---

By default, the paths `"classes/"` and `"include/"` are created. 
No classes are generated unless specifically provided.

---
### License ###
---

`new-php-project` is available under the <a href="LICENSE">MIT License</a>

