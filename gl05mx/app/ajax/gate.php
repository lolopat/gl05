<?php

	// Report all errors except E_NOTICE
	// This is the default value set in php.ini
	error_reporting(E_ALL ^ E_NOTICE);
		
	require_once( '../../includes/includes.inc.php' );
	
	header( 'Content-Type: text/plain; charset=utf-8' );
	header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
	header( 'Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0' );
	
	$response = array( 'status' => true );
	
	Language::setCurrentLanguage();
	
	switch ( substr( $_REQUEST['action'] , 0 , 50 ) ) 
	{
		case 'showContent':
				
			try
			{					
				if( !empty( $_REQUEST[ 'item' ] ) )
				{
						$project_name = $_REQUEST[ 'item' ] ;
						$plugin_name  = 'plg_menu_content_' . $project_name;		
						
						$plugin = new $plugin_name();
						
						switch( Language::getCurrentLanguage() )
						{
							case Language::LANGCODE_EN:
								$plugin->SetTemplate( $plugin_name . '_' . Language::LANGCODE_EN  );					
							break;
							case Language::LANGCODE_PL:
							default:
								$plugin->SetTemplate( $plugin_name );
							break;
						}
						
						$response[ 'result' ] = $plugin->OutputPage();										
				}
				
			}
			catch( Exception $ex )
			{
				Log::shortMessage( $ex , Log::LVL_ERROR , __METHOD__ );
				$response = new Ajax_Result( false );
			}
		
		break;
	}
	
	echo json_encode( $response );

?>