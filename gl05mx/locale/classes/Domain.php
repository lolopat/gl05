<?php
class Domain
{
	const DEV	= 'dev';
	const LIVE	= 'live';
	
	const DFLT	= 'default';
	
	const DEV_GL05	= '/^gl05.local$/';
	const LIVE_GL05	= '/^(www\.)?gl05\.eu$/';

	
	static $regexps = array(
		self::DEV 		=> array(
			Application::GL05			=> self::DEV_GL05,	
		),
		self::LIVE		=> array(
			Application::GL05			=> self::LIVE_GL05,	
		),	
	);
	
	static $domains = array(
	
		self::DEV => array(			
			Application::GL05 => array( self::DFLT => 'gl05.local' ),
		),

		self::LIVE => array(
			Application::GL05 => array( self::DFLT => 'www.gl05.eu' ),		
		),

	);
	
	/**
	 * pobiera aktulana nazwe hosta
	 *
	 * @return String
	 */
	public static function getHostName(){
		return $_SERVER['HTTP_HOST'];
//		return $_SERVER['SERVER_NAME'];
	}
	
	/**
	 * zwraca info o typie i nazwie aplikacji
	 *
	 * @return mixed
	 */
	public static function getInfo(){
		foreach ( self::$regexps as $appType => $ds )
		{
			foreach ($ds as $appName => $regex ) 
			{
				if( preg_match( $regex , self::getHostName() ) ) 
				{
					return array( 'type' => $appType, 'app' => $appName );
				}
			}
		} 
	}
	
	/**
	 * zwraca domene na podstawie info
	 *
	 * @param unknown_type $info
	 */
	public static function getDomain($info, $app=null){
		if(!$app){
			$app = $info['app'];
		}
		
		$domains = self::$domains[$info['type']][$info['app']];
		$ns = array_search(self::getHostName(), $domains);
//		show($ns);
		if(!($ret = self::$domains[$info['type']][$app][$ns])){
			$ret = self::$domains[$info['type']][$app][self::DFLT];
		}
		return $ret;
	}
	
	
	
	
	///////////////////////////////////////////////////////////////////
	
	var $domainParts = array();
	var $params = array();
	
	public function __construct($domain){
		$this->domainParts = explode('.', $domain);
	}
	
	public function __toString(){
		$domPtrs = $this->domainParts;
		array_walk( &$domPtrs , array( &$this , 'domainAttrRepl' ) , $this->params );
		return implode('.', $domPtrs);
	}
	
	public function domainAttrRepl(&$fragment, $no, $data){
		foreach($this->params as $paramName=>$paramVal){
//			show("$paramName, $paramVal, $fragment");
			$fragment = str_replace($paramName, $paramVal, $fragment);
		}
	}
}
?>
