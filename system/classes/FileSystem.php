<?php
class FileSystem {
	
	const PERMS_DEFAULT = 700;
	
	/**
	 * performs recursive path operations (on every subfolder)
	 * @param string $path
	 * @param callback $callback
	 * @param array $args
	 * @throws FileSystem_Exception
	 * @return array
	 */
	protected static function walkPath( $path, $callback, $args = null, $reverse = false ) {
		if( !is_array( $callback ) ) {
			$callback = array( __CLASS__, $callback );
		}
		if( !is_callable( $callback ) ){
			throw new FileSystem_Exception( 'Invalid callback!' );
		}
		$path	= str_replace( array( '/', '\\' ), '/', $path );
		$path	= trim( $path, '/' );
		$res	= array();
		$dirs	= explode( '/', $path );
		if( $reverse ) {
			while( count( $dirs ) > 0 ) {
				$r = call_user_func_array( $callback, array( $dirs, $args ) );
				$res[ array_pop( $dirs ) ] = $r;
			}
		} else {
			$tmp	= array();
			foreach( $dirs as $d ) {
				$tmp []= $d;
				$res[ $d ] = call_user_func_array( $callback, array( $tmp, $args ) );
			}
		}
		return $res;
	}
	
	/**
	 * @param string $path
	 * @param int $chmod
	 * @throws FileSystem_Exception
	 * @return array
	 */
	public static function createPath( $path, $chmod = self::PERMS_DEFAULT ) {
		return self::walkPath( $path, 'createDir', $chmod );
	}
	
	/**
	 * @param string $dir
	 * @param int $perms DECIMAL NUMBER!
	 * @throws FileSystem_Exception
	 * @return boolean
	 */
	public static function createDir( $dir = array(), $perms = self::PERMS_DEFAULT ) {
		while( is_array( $perms ) ) {
			$perms = reset( $perms );
		}
		if( is_array( $dir ) ) {
			$dir = implode( '/', $dir );
		}
		if( is_dir( $dir ) ){
			return false;
		}
		if( !@mkdir( $dir ) ) {
			throw new FileSystem_Exception( "Cannot create {$dir}" );
		}
		self::setPerms( $dir, $perms );
		return true;
	}
	
	/**
	 * @param string $path
	 * @throws FileSystem_Exception
	 * @return array
	 */
	public static function deletePath( $path ) {
		return self::walkPath( $path, 'deleteDir', true, true );
	}
	
	/**
	 * deletes a single directory
	 * @param array|string $dir
	 * @throws FileSystem_Exception
	 * @return boolean
	 */
	public static function deleteDir( $dir, $silent = false ) {
		if( is_array( $dir ) ) {
			$dir = implode( '/', $dir );
		}
		if( !is_dir( $dir = rtrim( $dir, '/' ) ) ){
			return false;
		}
		list( $cmd, $args, $ret ) = array( "rm -rdf {$dir}", array(), 0 );
		exec( $cmd, $args, $ret );
		if( $ret > 0 && !$silent ) {
			throw new FileSystem_Exception( "Cannot delete {$dir}" );
		}
		return !( $ret > 0 );
	}
	
	/**
	 * @param string $filePath
	 * @throws FileSystem_Exception
	 * @return boolean
	 */
	public static function deleteFile( $filePath ) {
		if( !file_exists( $filePath ) || !is_file( $filePath ) ) {
			return true;
		}
		if( !@unlink( $filePath ) ) {
			throw new FileSystem_Exception( "Unable to delete {$filePath}" );	
		}
		return true;
	}
	
	/**
	 * @param string $path
	 * @param int $perms
	 */
	public static function setPerms( $path, $perms = self::DEFAULT_PERMS ) {
		if( !@chmod( $path, octdec( $perms ) ) ) {
			throw new FileSystem_Exception( "Cannot set permissions to {$perms} on {$path}" );
		}
		return true;
	}
	/**
	 * Find file in path
	 *
	 * @param string $path
	 * @param string $pattern example(*.tpl);
	 * @return array
	 */
	public static function pathFind($path,$pattern, $maxDepthLevel = null){
		$addParams = array();
		if(!is_null($maxDepthLevel) && abs(intval($maxDepthLevel))>0){
			$addParams[] = "-maxdepth ".abs(intval($maxDepthLevel));
		}
		
		$addParams = implode(" ", $addParams);
		$cmd = "find {$path} -name '{$pattern}' {$addParams}";
		return array_filter(explode("\n", shell_exec($cmd)));
	}
}
?>