<?php
	
	// Report all errors except E_NOTICE
	// This is the default value set in php.ini
	error_reporting(E_ALL ^ E_NOTICE);
	
	require_once( 'includes/includes.inc.php' );
	
	//main plugin
	$CBase = new CBasePlugin();
	
       /**
	* perform request analyze
	*/
	$aq = Link_Analyze();
	
       /**
        * generate site, catch exceptions
	*/
	try 
	{	
		Language::setCurrentLanguage();
		
		//select the view to display
		$view_name = $aq['view'] ? $aq['view'] . "_view" : 'homepage_view';
	
		$site = new $view_name();
		$site->cache_key_params = $aq;
		
		/**
		  * those plugins are added to every view
		  */	
		//$site->AddModule('plg_meta_scripts');
		
		//site  backgrounds
		$backgrounds 	= glob( 'pics/background/*.*' );
		$random_number 	= array_rand( $backgrounds );
			
		$site->smarty->assign( 'background' , $backgrounds [ $random_number ] );
		
		$out = $site->GenerateSite();
	}
	catch( Exception $ex ) 
	{
		show( $ex );
		/*Debug::logError( 'index_php_exception', var_export( $ex , true ) . "\n" . strval( $ex->getTrace() ), false );
		//show plug - if set
		if( Config::get( 'show_plug_on_exception' ) ) {
			//redir( 'index.html' );
			exit( file_get_contents( 'locale/static/load.html' ) );
		}
		//display exceptions - if configured
		if( Config::get( 'display_exceptions' ) ) {
			show( $ex->getMessage() );
			show( $ex->getTrace() );
		}*/
	}
	
	PrintOutput( $out );
?>