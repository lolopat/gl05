<?php
	
	chdir( '/home/gl05/ftp/gl05eu' );
	
	$paths = array(
		get_include_path(),
		'app' . DIRECTORY_SEPARATOR . 'classes',
		'includes',
		'locale' . DIRECTORY_SEPARATOR . 'classes',
		'locale',
		'system' . DIRECTORY_SEPARATOR . 'classes',
		'system' . DIRECTORY_SEPARATOR . 'lib'
	);
	
	set_include_path( implode( PATH_SEPARATOR, $paths ) );
	
	require_once( 'autoload.inc.php' );
	require_once( 'functions.inc.php' );
	require_once( 'system' . DIRECTORY_SEPARATOR 
								. 'classes' 
									. DIRECTORY_SEPARATOR 
										. 'smarty' 
											. DIRECTORY_SEPARATOR 
												. 'libs' 
													. DIRECTORY_SEPARATOR 
														.'Smarty.class.php');
	require_once( 'links.inc.php' );
	
?>