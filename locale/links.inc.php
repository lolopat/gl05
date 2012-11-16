<?php

$_link_patterns = array(
	Application::GL05 => array(
		'homepage'	=> array( 'uri' => '/:subpage' , 'defaults' => array( 'subpage' => '' ) ),///gl05eunowa	
		'locations'	=> array( 'uri' => '/regionen/:country/:state/:range', 'defaults' => array( 'state' => '', 'range' => '' )),
	),

);
?>