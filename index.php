<?php
define ('BASEPATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
$framework='/framework_323/pradolite.php';
require_once ($framework);
$application = new TApplication;
$application->run();
?>