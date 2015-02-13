## new-php-project ##
---

Generates a basic PHP project in the current directory.  Optionally generates other directories, classes, unit tests, and miscellaneous files.

[![Build Status](https://travis-ci.org/patinthehat/new-php-project.png)](https://travis-ci.org/patinthehat/new-php-project)

---
### Usage ###
---
  Make sure you `chmod +x new-php-project.php` first, otherwise commands must be prefixed with `php -f new-php-project.php`.
  
  `$ new-php-project.php "project-name"`

  __Optional Flags__:
  
  - `-T`|`--tests` -  generate a "tests" directory, and 
    unit test files for any classes generated with `--classes`.
  - `-R`|`--readme` - generate a README file in markdown format.
  - `-h`|`--help` - show help/usage message.
  - `-U`|`--phpunit` - generate a PHPUnit configuration file. *Implies `--tests`.*
  - `-C|--coverage` - generate code coverage report, requires `--phpunit`.
  - `--gi`|`--gitignore` - generate an empty .gitignore file.
  - `--classes=<a,b,...>` - generate classes, comma separated.
  - `--paths=<a,b,...>` - generate additional paths, comma separated.
      
  __Examples__:

  - `new-php-project.php "project1"`  
  - `new-php-project.php "project1" --tests --classes=MyClass1,MyClass2`
  - `new-php-project.php --tests --classes=MyClass1 my-project-1`
  - `new-php-project.php --paths=docs,contrib myProject1`
  - *The following commands do the same thing:*
    - `new-php-project.php "project1" -RT`
    - `new-php-project.php "project1" --readme --tests`

---
### Defaults ###
---

By default, the following are created:

  - `classes/`
  - `include/`
  - `project.php` *(chmod +x)*
  - `autoload.php` 
  
  
No classes are generated unless specifically provided.

If `--tests` is passed AND classes are specified with `--classes`, unit test classes are generated in `tests/` for each class.

---
### License ###
---

`new-php-project` is available under the <a href="LICENSE">MIT License</a>

