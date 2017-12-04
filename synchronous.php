<?php
/**
synchronous
ver 1.0
*/

include __DIR__."/config.php";
include __DIR__."/synchronousClass.php";


$synchronousObject = new synchronous(
	$clientWebServer,
	$remoteWebServer
);


/**
 * Banner
 */
$synchronousObject->synchronousInfo($appInformation["appName"]);
$synchronousObject->parameterDisplay($appInformation);

/**
 * General-purpose execution
 */
if (empty($argv[1])) {
	$synchronousObject->getOption();
} else {
	$synchronousObject->getOption($argv[1]);
}



$source = $synchronousObject->dbsync();
var_dump($source);



/**==========================================
 * Get config list
 ==========================================*/




