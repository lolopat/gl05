<?php
class Ajax_Result extends Base_Result {
	
	public $actions = array();
	
	public function __construct( $success = false, $result = null, array $messages = array() ,array $actions = array()) {
		parent::__construct($success,$result,$messages);
		$this->actions = $actions;
	}
	
}
?>