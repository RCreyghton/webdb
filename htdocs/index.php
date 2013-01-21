<?php

//define access point
define("WEBDB_EXEC", true);
define("DS", "/");

/*
 * Register our autoloader
 */
spl_autoload_register('autoloader');

//parse the URL
//our basic url layout will be: /controllername/task
$parts = array_filter( explode( "/" , $_GET['q'] ) );
if ( empty( $parts ) ) {
	//this will be the homepage
	$controller = "threads";
	$task		= "unanswered";
} else {
	$controller = $parts[ 0 ];
	$task		= $parts[ 1 ];
}

if( is_file("./controllers/{$controller}.php") ) {
	$controller = "Controller_" . ucfirst( $controller );
	$controller = new $controller();
	$controller->execute( $task );
} else {
	$controller = Controller_Error();
	$controller->execute( "notfound" );
}

/**
 * This function is given a string representation of a classname
 * and attempts to include it. Throws an exception if the requested class if not
 * of a specific type or if the file is not found.
 * 
 * @param type $classpath
 * @throws Exception
 * @throws RuntimeException
 */
function autoloader( $classpath ) {
	
	$allowedTypes = array(
		'Models',
		'Controllers',
		'Helpers',
		'Views'
	);
	
	$parts = explode("_", $classpath);
	if( ! in_array( $parts[0], $allowedTypes ) ) {
		throw new Exception( "Improper type: " . $parts[0] );
	}
	
	//convert to path
	$path = str_replace( "_", DS, $classpath ) . ".php";
	
	if (is_file( $path )) {
		include $path;
	} else {
		throw new RuntimeException( "Could not locate classfile: " . $path );
	}
}