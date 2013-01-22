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
//TODO check whether q exists or catch this at htaccess
$parts = array_filter( explode( "/" , $_GET['q'] ) );
if ( empty( $parts ) ) {
	//this will be the homepage
	$controller = "Threads";
	$task		= NULL; //empty by default,  controller->execute must be able to handle this?
} else {
	$controller = ucfirst(strtolower( $parts[ 0 ] ));
	$task		= ( sizeof( $parts ) > 1 ) ? strtolower( $parts[ 1 ] ) : "";
}

if( is_file("./controllers/{$controller}.php") ) {
	$controller = "Controllers_" . $controller ;
	$controller = new $controller();
	$controller->execute( $task );
} else {
	$controller = Controllers_Error();
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
	if( ! in_array( $parts[0], $allowedTypes ) ) {
		throw new Exception( "Improper type: " . ucfirst($parts[0]) );
	}
	
	//convert to path
	
	//TODO handling extra or trailing slashes!!!
	//tried to fix broken filenames (lowercase etc) on unix.
	$parts[count($parts) - 1] = ucfirst( $parts[count($parts) - 1] );
	$path = implode( DS, $parts ) . ".php";
	
	if (is_file( $path )) {
		include $path;
	} else {
		throw new RuntimeException( "Could not locate classfile: " . $path );
	}
}