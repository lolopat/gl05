<?php

	/**
	 * wyswietla przekazana zmienna
	 * 
	 * @param mixed v zmienna do wyswietlenia
	 * @param bool dump_mode gdy 'true', wykonywany jest zwykly var_dump
	 */
	function show( $v, $dump_mode = false ) 
	{
		$ret = "";
		$ret .= "<pre style='text-align:left;'>";
	    if( !$dump_mode ) {
	    	switch( gettype( $v ) ) {
	    		case 'array':
	    			$ret .= "<b>";
	    			$debug = debug_backtrace();
					$ret .= print_r(str_replace('/var/www','',$debug[0]['file'].'::'.$debug[0]['line']), true);
	    			$ret .= "</b>\n";
					$ret .= print_r($v, true);
	    			break;
	    		default:
	    			$ret .= "<b>";
	    			$debug = debug_backtrace();
					$ret .= print_r(str_replace('/var/www','',$debug[0]['file'].'::'.$debug[0]['line']), true);
	    			$ret .= "</b>\n";
	    			$ret .= var_export($v, true);	
	    	}
	    } else {
			$ret .= "<b>";
	    	$debug = debug_backtrace();
			$ret .= print_r(str_replace('/var/www','',$debug[0]['file'].'::'.$debug[0]['line']), true);
	    	$ret .= "</b>\n";
	    	$ret .= var_dump($v, true);
	    }
	    $ret .= '</pre>';
	    
	    echo $ret;
	}
	
	function Link_Create( $link, $params = false, $application = null, $add_domain = false ) {    
	global $_seo_separator;
	$appInfo = Domain::getInfo();

	if(empty($application)){
		$application = $appInfo['app'];
	}
	
	$_link_patt = $GLOBALS['_link_patterns'][$application];
	
	if( $params ) { 
		foreach( $params as &$v ) {
			$v = preg_replace("/\//","",$v);
			$v = preg_replace("/%2F/","",$v);					    			
		}
	}
	

	if (!is_string($link) && !is_int($link)) {
		Debug::logError('links', 'ERROR: Link type should be eighter string type or integer type ('.gettype($link).' given)');
		exit;
	}
	
	if ( array_key_exists( $link, $_link_patt ) ) {
		$ret_url = $_link_patt[$link]['uri'];
	} else {
		Debug::logError('links', 'ERROR: Unknown link type ('.$link.')');
		exit;
	}    
	
	if ( is_array( $_link_patt[$link]['defaults'] ) ) {
		foreach( $_link_patt[$link]['defaults'] as $key => $val ) {
			if ( empty($params[ $key ]) ) {
				$params[$key] = $val;
			}
		}
	}
	
	if(empty($params['seo'])){
		$params['seo'] = ':seo';
	};
	
	// wypelniamy pattern parametrami
	if ( is_array( $params ) ) {
		array_walk( $params, '_Fill_Link_Params', &$ret_url );
	}
	// begin seo
	if(preg_match('/:seo/', $ret_url)){
		$seo = SEO_Create($link, $params, $application);
		$ret_url = str_replace(':seo', $seo, $ret_url);
	}	
	// end seo
	
	// sprawdzamy czy wszystkie parametry sa wypelnione
	if ( preg_match('/([:]{1}.+)/', $ret_url ) ) {
		Debug::logError('links', 'ERROR: Some parameters hasn\'t values ('.$ret_url.')');
		exit;
	}
	if ( strlen( $ret_url ) > 1 ) {
		$ret_url = rtrim($ret_url, '/');
	}
	
	$domain = Domain::getDomain($appInfo, $application);
	if(preg_match('/:seo/', $_link_patt[$link]['uri'])){
		$ret_url = ltrim($ret_url, "/");
		$ret_url = "/". str_replace("/", $_seo_separator, $ret_url);
	}

	if($add_domain || $application != $appInfo['app']){
		$ret_url = "http://" . $domain . $ret_url;
	}
	$separators = array('/', $_seo_separator);
	$separators = implode('|', $separators);

	$bad		= array ( "@(({$separators})[0-])+$@i" );
	$good		= array ( '' );
	
	return preg_replace( $bad, $good, $ret_url );
}

	function _Fill_Link_Params( $param, $key, $link ) {
	global $translate_table;
	$param = trim($param) != '' ? $param : '-';
	if ( !is_array( $param ) ) {
		if( isset( $translate_table[ $param ] ) ) {
			$param = $translate_table[$param];
		}
		$link = str_replace(':'.$key, $param, $link);    
	} else {
		$link .= '?';
		foreach( $param as $k => $v ) {
			$link .= "{$k}={$v}&";
		}
		$link = rtrim($link, '&');
	}
}
	
	function Link_Analyze( $request = '' ) 
	{
		$appInfo = Domain::getInfo();
		
		$_link_patt = $GLOBALS['_link_patterns'][$appInfo['app']];
		
		global $translate_table, $_seo_separator;
		
		//default view
		$data = array(
			'view' 		=> 'homepage', 
			'params'	=> array(),
		);
		
		//usuwamy query string z url
		$request_uri = rtrim( preg_replace( '/\?.*$/', '', ( ($request == '' ) ? $_SERVER['REQUEST_URI'] : $request ) ), '/' );
		$request_uri = str_replace($_seo_separator, "/", $request_uri);

		//check pattern
		foreach ( $_link_patt as $view => $pattern ) 
		{
			$matches            = array();
			$pattern_matches    = array();
			
			if ( isset( $pattern['defaults'] ) ) 
			{
				//jesli sa zdefiniowane defaultowe wartosci parametrow, to przyjmujemy, ze sa one opcjonalne
				$url_params_pattern = preg_replace( '|/([:][a-z\_]+)|' , '/?([^/]*)' , $pattern[ 'uri' ] );
			}
			else 
			{
				// ...w innym wypadku sa one konieczne
				$url_params_pattern = preg_replace( '|([:][a-z\_]+)|' , '([^/]*)', $pattern[ 'uri' ] );
			}

			if ( preg_match( '|^' . $url_params_pattern . '$|', $request_uri, $matches ) ) 
			{
				//nazwy parametrow
				preg_match_all( '|([:][a-z\_]+)|', $pattern['uri'], $pattern_matches );
				
				//usuwamy pierwsze elementy z tablic zwroconych przez
				//preg_match (pierwszy element to pattern dopasowany do testowanego stringa)
				array_shift( $matches );
				array_shift( $pattern_matches );
				$params = array();
			
				foreach ( $matches as $key => $val ) 
				{
					if ( $val == '-' ) 
					{
						$val = '';
					}
					
					if( in_array( $val, $translate_table ) ) 
					{
						$val = array_search( $val, $translate_table );
					}
			    	$params[substr($pattern_matches[0][$key], 1)] = $val;
				}
				
				//domyslne wartosci parametrow
				if( isset( $pattern['defaults' ] ) )
				{
					if ( is_array( $pattern['defaults' ] ) ) 
					{
						foreach ( $pattern['defaults'] as $key => $val ) 
						{
							if ( $params[ $key ] == '' ) 
							{
								$params[$key] = $val;
							}
						}
					}
				}
				$data['view']   = $view;
				$data['params'] = $params;

				break;
			}
		}
		
		if( !isset( $data[ 'params' ][ 'cat' ] ) && isset( $GLOBALS[ 'cfg_main' ][ 'view2catId' ][ $data[ 'view' ] ] ) ) 
		{
			$data['params']['cat'] = $GLOBALS['cfg_main']['view2catId'][$data['view']]; 
		}

		return $data;
}


/**
 * Wypisuje podany string do przegladarki
 * ustawia odpowiednie naglowki size, itd
 * umo?liwia odpowied? na z?danie wys?ania cz?ci pliku
 * mo?e byc uzyta tylko raz w ca?ym skrypcie, 
 * 
 * 
 * @param string $data
 */
function PrintOutput( $data ) 
{
	echo $data;
}

/**
 * wykonuje przekierowanie na podanego linka,
 * uprzednio zapisujac sesje i wysylajac
 * wszystkie potrzebne headery
 */
function redir( $link, $code = 303){
	switch ($code) {
		case 404:
			header('HTTP/1.0 404 Not Found');
			header('Status: 404 Not Found');
			break;
			
		case 301:
			header('HTTP/1.1 301 Moved Permanently');
			break;
			
		/*default:
			header('HTTP/1.1 303 See Other');
			header('Status: 303 See Other');
		break;*/
	}
	
	header( 'Location: ' . $link );
	die();
}

?>