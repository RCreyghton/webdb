<?php

//define access point
define("WEBDB_EXEC", true);
define("DS", "/");
define("BASE", getcwd() . DS );

/*
 * Register our autoloader
 */
spl_autoload_register('autoloader');

//parse the URL
//our basic url layout will be: /controllername/task
$q = isset( $_GET['q'] ) ? $_GET['q'] : '';
$parts = array_filter( explode( "/" , strtolower( $q ) ) )  ;

if ( empty( $parts ) ) {
	//this will be the homepage
	$controller = "Threads";
	$task		= "unanswered";
} else {
	$controller = ucfirst( $parts[ 0 ] );
	$task		= ( isset( $parts[ 1 ] ) ) ? $parts[ 1 ] : NULL ;
}

if( is_file("./controllers/{$controller}.php") && ! empty( $task ) ) {
	$controller = "Controllers_" . $controller ;
	$controller = new $controller();
	$controller->parseParts( $parts );
	$controller->execute( $task );
} else {
	$controller = new Controllers_Error();
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
		'models',
		'controllers',
		'helpers',
		'views'
	);
	
	$parts = explode("_", strtolower($classpath));
	
	//check if classpath is allowed
	if( ! in_array( $parts[0], $allowedTypes ) ) {
		throw new Exception( "Improper type: " . ucfirst($parts[0]) );
	}
	
	//convert to path
	$path = implode( DS , array_map( function ( $part ) use ( $parts ) { 
		return end( $parts ) == $part ? ucfirst( $part ) : $part;
    } , $parts ));
	
	$path .= ".php";
		
	if (is_file( $path )) {
		include $path;
	} else {
		throw new RuntimeException( "Could not locate classfile: " . $path );
	}
}
