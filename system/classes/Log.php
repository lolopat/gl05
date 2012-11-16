<?php
final class Log {
	
	const LOG_DIR				= './data/logs/';
	const TMP_DIR				= '/tmp/';
	const FILE_REQUESTS			= 'MP_Requests.log';
	const FILE_RSS				= 'MP_catched_rss.log';
	const FILE_SLOW				= 'MP_slow.log';
	const FILE_SLOW_DETAILS		= 'MP_slow_details.log';
	const FILE_TIMES			= 'MP_times.log';
	const FILE_LOAD				= '/proc/loadavg';
	const FILE_CACHE			= 'cache.log';
	
	private static $DEBUG_LVL	= self::LVL_INFO;
	
	const LVL_NONE				= 10;
	const LVL_ERROR				= 3;
	const LVL_INFO				= 2;
	const LVL_NOTICE			= 1;
	
	const MAX_SIZE				= 1000000;
	const MAX_DAYS				= 3;
	
	/**
	 * @author Rafal.Drag
	 * @return string
	 */
	public static function today(){
		return date("Y-m-d");
	}
	
	/**
	 * @author Rafal.Drag
	 * @return string
	 */
	public static function now(){
		return date("H:i:s");
	}
	
	/**
	 * @author Rafal.Drag
	 * @return string
	 */
	private static function IP(){
		$ip = $_SERVER['REMOTE_ADDR'];
		return "Remote IP: [{$ip}]";
	}
	
	/**
	 * @author Rafal.Drag
	 * @return string
	 */
	private static function req(){
		return $_SERVER['REQUEST_URI'];
	}
	
	/**
	 * gets name for file, when the filesize is greater then self::MAX_SIZE it adds postfix with no.  
	 * 
	 * @param string $file
	 * @return string
	 */
	public static function getFileName( $file ){
		$pathinfo 			= pathinfo( $file );
		list( $name, $dir ) = array( $pathinfo['filename'], $pathinfo['dirname'] );
		$files 				= glob( $dir . '/' . $name . '*.log' );
		if( count( $files ) ){			
			if( filesize( end( $files ) ) > self::MAX_SIZE ){
				$no = str_pad( count( $files ), 4, '0', STR_PAD_LEFT );
				return $dir . '/' . $name . '_' . $no . '.log';
			}
			return end( $files );
		}
		return $file;
	}
	
	/**
	 * used to compress folders with logs older than MAX_DAYS
	 */
	protected static function compressOlderDirs(){
		// check if there are old dirs to pack
		$dirs	= array_reverse( glob( self::LOG_DIR . '*', GLOB_ONLYDIR ) );
		//don't delete folders from last days
		$dirs	= array_slice( $dirs, self::MAX_DAYS );
		foreach ( $dirs as $d ) {
			$ret = 0;
			passthru( "tar -czf {$d}.tgz {$d}", $ret );
			//if packing succeeded, delete log directory
			if( $ret == 0 ) {
				FileSystem::deleteDir( $d, true );
			}
		}
	}
	
	/**
	 * @author Rafal.Drag
	 * @author Ireneusz.Bialek
	 * @param string $fn log filename
	 * @param string $txt data to log
	 */
	private static function write( $fn, $txt, $dir = self::TMP_DIR ) {
		if( $dir == self::LOG_DIR ){
			$dir .= self::today() . '/';
			if( !is_dir( $dir ) ) {
				FileSystem::createPath( $dir, 770 );
				
				// set group to www-data
				chgrp( $dir, 'www-data' );
				
				// this is first log (for today), so compress older log folders
				#self::compressOlderDirs();
			}
			@file_put_contents( self::getFileName( $dir . self::today() . $fn ), "$txt\n", FILE_APPEND );
			return;	
		}
		@file_put_contents( $dir . self::today() . $fn, "$txt\n", FILE_APPEND );
	}
	
	/**
	 * log current request
	 * @author Rafal.Drag
	 */
	public static function request(){
		$txt	= self::now() . " " . self::IP() . ":\t" . self::req();
		self::_doWrite( self::getFileName( self::TMP_DIR . date('Y-m-d_') . self::FILE_REQUESTS ), $txt );
	}
	
	/**
	 * log RSS request
	 * @author Rafal.Drag
	 */
	public static function rss(){
		$txt	= self::now() . ' ' . self::IP(). "]:\t" . self::req() . "\nReferer: " . $_SERVER['HTTP_REFERER'];
		self::write( self::FILE_RSS, $txt );
	}
	
	/**
	 * log 'slow' request
	 * @param int $page_stop
	 * @param int $page_start
	 */
	public static function slow( $page_stop, $page_start ){
		global $CBase;
		
		$time	= round( ( $page_stop - $page_start ), 2 );
		$load	= substr( file_get_contents( self::FILE_LOAD ), 0, 4 );
		
		$txt	= self::now() . ' ' . self::IP() . ":\t[ time: {$time}s ] [ load: {$load} ]\tURI: " . self::req();
		self::write( self::FILE_SLOW, $txt );
		
		if( is_object( $CBase ) ) {
			$txt	.= "\n" . var_export( $CBase->ReturnTimeTable(), true );
			self::write( self::FILE_SLOW_DETAILS, $txt );
		}
	}
	
	/**
	 * universal method for dumping AJAX incoming/outgoing data
	 * @param mixed $data
	 * @param string $title
	 */
	public static function AJAXData( $data, $title = null ) {
		$txt = self::today() . ' ' . self::now() . "\n" . var_export( $data, true ) . "\n--------------------";
		if( $title != null ) {
			$txt = "{$title}:\n{$txt}";
		}
		self::write( '_ajax_dump.log', $txt, self::LOG_DIR );
	}
	
	/**
	 * universal message logging
	 * @param mixed $msg
	 * @param int $level
	 */
	public static function message( $msg, $level = self::LVL_NOTICE, $file = 'messages.log' ) {
		if( self::$DEBUG_LVL == null ) {
			self::$DEBUG_LVL = Config::get( 'debug_level' );
		}
		if( $level >= self::$DEBUG_LVL ) {
			$txt = self::today() . ' ' . self::now() . "\n" . var_export( $msg, true ) . "\n--------------------" . var_export( Debug::simpleBacktrace(), true ) . "\n--------------------";
			self::write( '_' . preg_replace( '/^_/', '', $file ), $txt, self::LOG_DIR );
		}
	}
	
	/**
	 * universal short message logging
	 * @param mixed $msg
	 * @param int $level
	 */
	public static function shortMessage( $msg, $level = self::LVL_NOTICE, $file = 'short_messages.log' ) {
		if( self::$DEBUG_LVL == null ) {
			self::$DEBUG_LVL = Config::get( 'debug_level' );
		}
		if( $level >= self::$DEBUG_LVL ) {
			$txt = self::today() . ' ' . self::now() . "\n" . var_export( $msg, true ) . "\n--------------------";
			self::write( '_' . preg_replace( '/^_/', '', $file ), $txt, self::LOG_DIR );
		}
	}
	
	/**
	 * universal text message logging
	 * @param mixed $msg
	 * @param int $level
	 */
	public static function plainText( $msg, $level = self::LVL_NOTICE, $file = 'text_messages.log' ){
		if( self::$DEBUG_LVL == null ) {
			self::$DEBUG_LVL = Config::get( 'debug_level' );
		}
		if( $level >= self::$DEBUG_LVL ) {
			self::write( '_' . preg_replace( '/^_/', '', $file ), $msg, self::LOG_DIR );
		}
	}

	/**
	 * writes $txt to $file
	 *
	 * @param string $file
	 * @param string $txt
	 */
	protected static function _doWrite( $file, $txt ){
		@file_put_contents( $file, "$txt\n", FILE_APPEND );
	}
	
	/**
	 * writes log about page generation time
	 *
	 * @param float $time
	 */
	public static function times( $time ){
		$txt = date("H:i:s") . " Remote IP: [".$_SERVER['REMOTE_ADDR'] . "]:\t[" . " time: " . round( ( $time ), 3)."s ]\t\tRequserURI: ".$_SERVER['REQUEST_URI'];
		self::_doWrite( self::getFileName( self::TMP_DIR . date('Y-m-d_') . self::FILE_TIMES ), $txt );
	}
	
	/**
	 * writes log about cache 
	 *
	 * @param string $msg
	 */
	public static function cache( $msg ){
		self::write( '_' . preg_replace( '/^_/', '', self::FILE_CACHE ), $msg, self::LOG_DIR );
	}
	
	/**
	 * writes debug log 
	 *
	 * @param string $msg
	 * @param string $filename
	 */
	public static function debug( $msg, $filename = 'debug.log' ){
		self::write( '_dbg_' . preg_replace( '/^_/', '', $filename ), $msg, self::LOG_DIR );
	}
	
	/**
	 * write admin activity log
	 *
	 * @param string $operation
	 * @param array	$data
	 * @param string $exception
	 */
	public static function activity( $operation, $data = null, $exception = null ){
		if (!$data) {$data=$_POST;}
		$msg = "-----------------------------------------\n";
		$msg .= 'Date - time		: '.self::today().' '.self::now()."\n";
		$msg .= 'Loged Admin ID		: '.$_SESSION['admin']['id']."\n";
		$msg .= 'Operation		: '.$operation."\n";
		$msg .= 'Data			: '.print_r($data, true)."\n";
		$msg .= 'Ex				: '.($exception?$exception:'none')."\n";
		$msg .= "-----------------------------------------";
		self::write( '_AdminActivity.log', $msg, self::LOG_DIR );
	}
}
?>