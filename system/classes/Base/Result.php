<?php
/**
 * Base_Result is a stub of container for holding
 * results incoming from an operation
 * 
 * @author Rafal.Drag
 */
abstract class Base_Result {
	
	/**
	 * indicates whether or not an operation ended successfully
	 * 
	 * @var bool
	 */
	public $success;
	
	/**
	 * holds information about data within the result
	 * 
	 * @var mixed
	 */
	public $result;
	
	
	/**
	 * holds messages that refer to the operation
	 * @var array
	 */
	public $messages;
	
	/**
	 * default constructor
	 */
	public function __construct( $success = false, $result = null, array $messages = array() ) {
		$this->success	= $success;
		$this->result	= $result;
		$this->messages	= $messages;
	}
}
?>