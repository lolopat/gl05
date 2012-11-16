<?php
class CViewModule {
    public $name;
    public $frame_tpl;
    public $params;
    
    function __construct( $name, $frame_tpl, $params ) {
		$this->name			= $name;
		$this->frame_tpl	= $frame_tpl;
    	$this->params		= $params;
    }
}
?>