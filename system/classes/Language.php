<?php
class Language 
{
	const LANGCODE_PL = 'pl';
	const LANGCODE_DE = 'de';
	const LANGCODE_EN = 'en';
	const LANGCODE_ES = 'es';
	const LANGCODE_FR = 'fr';

	
	private static $current_language = null;

	public static function getCurrentLanguage()
	{		
		return self::$current_language;
	}
	
	public static function setCurrentLanguage()
	{
		if( !empty( $_GET[ 'language' ] ) && in_array( $_GET[ 'language' ] , self::getLanguages() ) )
		{
			$month = 2592000 + time();
			$hour  = time()+3600;
			
			setcookie( "language" , $_GET[ 'language' ] , $hour );
			
			self::$current_language = $_GET[ 'language' ];
			
			redir( '/' );
		}
		else
		{
			if( isset( $_COOKIE[ 'language' ] ) && in_array( $_COOKIE[ 'language' ] , self::getLanguages() ) )
			{
				self::$current_language = $_COOKIE[ 'language' ];	
			}
			else
			{
				self::$current_language = self::LANGCODE_PL;
			}
		}		
	}
	
	public static function getLanguages()
	{
		return array( self::LANGCODE_PL,
					  self::LANGCODE_DE,
					  self::LANGCODE_EN,
					  self::LANGCODE_ES,
					  self::LANGCODE_FR,);
	}
};

?>