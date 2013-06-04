<?php
error_reporting(E_ALL | ~E_NOTICE);
ini_set("display_errors", "true");
ini_set("soap.wsdl_cache_enabled", 0);

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
