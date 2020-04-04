# Git Opencart Pack

Git plugin script to create an opencart plugin zip file from a git repository

## Getting Started

For this to work, the source that is being packed must have an `upload` folder
containing files in the correct directory structure to upload to opencart.

### Prerequisites

Intended for use on a linux system with bash. Not tested on windows.
For converting vqmod to ocmod, php-cli must be enabled.

### Installing

Copy git-ocpack and convert.php files to your bin folder and make sure they are executable.

### Usage
Open the terminal in the git directory you want to pack,
and run the command `git ocpack`.

Takes the following optional parameters:
* `-p` Convert vqmod to ocmod
* `-q` Convert ocmod to vqmod
* `-d <path-to-destination>` Where to save the .ocmod.zip file
* `-h` Show usage options
* `-v` Display version

Default behaviour is to generate an ocmod plugin if there is a [install.xml] file present.
If this file is not found, a vqmod type plugin will be generated.
When using `-p` option, there must be a vqmod folder with only 1 xml file. This file will
be converted to the install.xml file required for a ocmod plugin.
When using the `-q` option, the [install.xml] file will be moved to the vqmod folder
and renamed to the project name.

When generating a ocmod plugin, the directory structure will be pre-checked for any 
invalid folders. If any are found, it will notify and abort.

## Authors

* **Joe Rothrock** - *Initial work* - [akmjoe](https://github.com/akmjoe)

## License

This project is licensed under the GPL License - see the [LICENSE.md](LICENSE.md) file for details
