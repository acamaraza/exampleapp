Example Application
===================

Introduction
------------
This is a simple REST API - REST Client application using the ZF2 MVC layer and BackboneJS.
It consists in an server side minimal REST API and a client side BackboneJS app both build
from scratch.

It is also responsive and 'mobile friendly'.

Purposes
--------
This application is meant to be used as an simple example


Installation
------------

### Dependencies

This application uses `composer` (https://getcomposer.org/) for managing dependencies.
You can simply use the shipped `composer.phar` in the project root for installing
required dependencies:

    cd my/project/dir
    php composer.phar install


### DB set up

Go to you MySQL and run the script file 'exampleApp_db.sql' shipped in the project root.
It is a single table database script with sample data for illustrating the REST API
database manipulation.

### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-tutorial.localhost
        DocumentRoot /path/to/exampleapp/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/exampleapp/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>


Enjoy!