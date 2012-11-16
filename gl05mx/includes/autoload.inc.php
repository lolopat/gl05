<?php
	
	function __autoload( $class_name ) 
	{	
		if( preg_match( '/^plg_/', $class_name ) ) 
		{
			$fn = str_replace( '_', DIRECTORY_SEPARATOR , preg_replace( '/^plg_/', '', $class_name ) );
			$fn = 'app' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . "{$fn}.php";
		} 
		elseif ( preg_match( '/_view$/', $class_name ) ) 
		{
			$fn = 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . str_replace( '_view' , '' , $class_name ) . '.view.php';
		} 
		else 
		{
			$fn = str_replace( "_" , DIRECTORY_SEPARATOR , $class_name ) . '.php';			
		}
		
		require_once( $fn );
	}
?>