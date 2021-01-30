<?php

date_default_timezone_set('Asia/Jakarta');

define ('BASEPATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
$framework='framework/pradolite.php';
require_once ($framework);
$application = new TApplication();
$application->run();