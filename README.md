# new-php-project #
---

Generates a basic PHP project in the current directory.  Optionally generates other directories, classes, unit tests, and miscellaneous files.

[![Build Status](https://travis-ci.org/patinthehat/new-php-project.png)](https://travis-ci.org/patinthehat/new-php-project)

_Quick start: Jump to the <a href="#configuration">Configuration Overview</a> to get started right away._

---
## Usage ##
---

  _Make sure you `chmod +x new-php-project.php` first, otherwise commands must be prefixed with `php -f new-php-project.php`._
  
  _To make `new-php-project` easier to use, it's recommended to add the directory that `new-php-project` is located in to your `$PATH` environment variable._
  
From the command line:

  `$ new-php-project.php "project-name"`

  __Optional Flags__:
  
  - `-T`|`--tests` -  generate a "tests" directory, and 
    unit test files for any classes generated with `--classes`.
  - `-R`|`--readme` - generate a README file in markdown format.
  - `-L`|`--license` - generate an empty license file.
  - `-h`|`--help` - show help/usage message.
  - `-U`|`--phpunit` - generate a PHPUnit configuration file. *Implies `--tests`.*
  - `-C`|`--coverage` - generate code coverage report, requires `--phpunit`.
  - `-X`|`--exec` - add a hashbang line to the `project.php`, and `chmod +x project.php`.  Use this option to enable the `project.php` to be executable from the command line.
  - `--gi`|`--gitignore` - generate an empty .gitignore file.
  - `--gitignore=<a,b,...>` - generate a .gitignore file using the gitignore.io api service.
  - `--license=<license-name>` - generate a license file for the specified license using the provided license templates.
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
## Configuration ##
---

### Overview ###

  **To get started quickly:**
  
  - Rename `new-php-project.json.dist` to `new-php-project.json`;
  - Edit the settings in `new-php-project.json` to include your information.
  
### Explanation ###

`new-php-project` uses a JSON configuration file named `new-php-project.json`.
By default, the github repository includes a file named **`new-php-project.json.dist`**; ** This file should be renamed to `new-php-project.json` after configuring the settings. **

**You must modify this file and add your information to the defined settings.**

These settings include: "author", "email", and "homepage".  The values of these settings are used for generating the license file and license header, if `-L|--license` is specified on the command line.

**License generation and other features may not work as expected if the configuration file is not set up properly.**

  - `default-paths` specifies the default paths to create, in addition to paths specified with `--paths`. Value should be a comma delimited string.
  - `tests-path` specifies the name of the tests directory to create, if any.  Defaults to "tests".


---
## Defaults ##
---

By default, the following are created:

  - `classes/`
  - `include/`
  - `project.php`
  - `autoload.php` 
  
The `classes/` and `include/` paths are defined in the `new-php-project.json` configuration, and can be modified to meet your needs.
  
No classes are generated unless specifically provided.

If `--tests` is passed AND classes are specified with `--classes`, unit test classes are generated in `tests/` for each class.

---
## `.gitignore` Generation ##
---

By default, the php error log file is added to the `.gitignore` file _(ini setting "`error_log`")_.

If you choose to generate a `.gitignore` file and specify a value _(`--gitignore=a,b,c`)_, `new-php-project` will access the <a href="https://www.gitignore.io">gitignore.io</a> api service.  For a full list of acceptable values, visit <a href="https://www.gitignore.io/api/list">gitignore.io/api/list</a>.

#### Example values: ####

  The specified value must be a comma-delimted list.
  
  - Some items include `linux`, `windows`, `eclipse`, `c`, `c++`.
  - i.e.: `--gitignore=linux,eclipse,c`


---
## `LICENSE` Generation ##
---

`new-php-project` allows you to automatically generate a `LICENSE` file using the `--license=<license-name>` argument.  Several templates are included for some of the most popular licenses.  License templates are stored in `data/licenses/`.

_@TODO: add template format specification_


---
## TODO ##
---

  - [ ] Refactor application code to separate class
  - [x] Add support for generating license files and headers
  - [x] Move `project.php` executable support to a command line flag
  - [ ] Add more license templates
  - [ ] Add support for generating web applications
  - [ ] Add support for generating arbitrary files
  - [ ] PSR-4 autoloading support
  
---
## Project Licensing ##
---

`new-php-project` is available under the <a href="LICENSE">MIT License</a>.

