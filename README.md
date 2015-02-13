## new-php-project ##
---

Generates a basic PHP project in the current directory.  Optionally generates other directories, classes, unit tests, and miscellaneous files.

[![Build Status](https://travis-ci.org/patinthehat/new-php-project.png)](https://travis-ci.org/patinthehat/new-php-project)

---
### Usage ###
---

  _Make sure you `chmod +x new-php-project.php` first, otherwise commands must be prefixed with `php -f new-php-project.php`._
  
  _To make `new-php-project` easier to use, it's recommended to add the directory that `new-php-project` is located in to your `$PATH` environment variable._
  
  `$ new-php-project.php "project-name"`

  __Optional Flags__:
  
  - `-T`|`--tests` -  generate a "tests" directory, and 
    unit test files for any classes generated with `--classes`.
  - `-R`|`--readme` - generate a README file in markdown format.
  - `-h`|`--help` - show help/usage message.
  - `-U`|`--phpunit` - generate a PHPUnit configuration file. *Implies `--tests`.*
  - `-C|--coverage` - generate code coverage report, requires `--phpunit`.
  - `--gi`|`--gitignore` - generate an empty .gitignore file.
  - `--gitignore=<a,b,...>` - generate a .gitignore file using the gitignore.io api service.
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
### Generation of `.gitignore` ###
---

By default, the php error log file is added to the `.gitignore` file _(ini setting "`error_log`")_.

If you choose to generate a `.gitignore` file and specify a value _(`--gitignore=a,b,c`)_, `new-php-project` will access the <a href="https://gitignore.io">gitignore.io</a> api service.  For a full list of acceptable values, visit <a href="https://gitignore.io/api/list">gitignore.io/api/list</a>.

Example values: `linux`, `windows`, `eclipse`, `c`, `c++`.

---
### License ###
---

`new-php-project` is available under the <a href="LICENSE">MIT License</a>

