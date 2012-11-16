<?php
class Application {
	
	const GL05	= 'gl05';
	
	/**
	 * @return string
	 */
	public static function getName(){
		$info = Domain::getInfo();
		return $info['app'];
	}
}
?>