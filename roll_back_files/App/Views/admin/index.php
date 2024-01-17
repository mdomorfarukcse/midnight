<?php

require dirname(__DIR__) . '/vendor/autoload.php';

set_error_handler('Pemm\Core\Error::errorHandler');
set_exception_handler('Pemm\Core\Error::exceptionHandler');
//date_default_timezone_set("Europe/Istanbul");
//date_default_timezone_set("Europe/Vilnius");

session_start();

include dirname(__DIR__) . '/App/Core/init.php';
