Magento Composer Autoloader
===========================
Implemented by overriding the core/cache model in the first configuration merge stage. So it is always available, no matter how Magento is instantiated.
 
If you want to disable a module, you have to change name of `app/etc/ecomdev_composerautoload.xml` to something not detectable by Magento.

Does not conflict with any custom implementation of Magento autoloader, since only wraps existing autoloader with suppress warning.

System Requirements
-------------------
* PHP 5.3 and above
* Magento 1.4.x and above

Build Status
------------
* Latest Release: [![Master Branch](https://travis-ci.org/EcomDev/EcomDev_ComposerAutoload.png?branch=master)](https://travis-ci.org/EcomDev/EcomDev_ComposerAutoload)
* Development Branch: [![Development Branch](https://travis-ci.org/EcomDev/EcomDev_ComposerAutoload.png?branch=develop)](https://travis-ci.org/EcomDev/EcomDev_ComposerAutoload)
* Code Coverage: [![Coverage Status](https://img.shields.io/coveralls/EcomDev/EcomDev_ComposerAutoload.svg)](https://coveralls.io/r/EcomDev/EcomDev_ComposerAutoload)

Installation
------------
Install module via composer:

 ```json
 {
     "require": {
        "ecomdev/composer_autoload": "*"
     },
     "repositories": [
         {
             "type": "composer",
             "url": "http://packages.firegento.com"
         }
     ]
 }
 ```


Contributions
-------------

If you want to take a part in improving our extension please create branches based on develop one. 

###Create your contribution branch: 
   
        $ git checkout -b [your-name]/[feature] develop

Then submit them for pull request. 
