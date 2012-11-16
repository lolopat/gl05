<?php
class plg_meta_scripts extends CBasePlugin {

	protected $initLangModule = false;
	
    	function DoActions() {
 		$comments	= $this->cfg['show_plugin_comments'];
 		//$compress	= Config::get( 'JavaScript', 'compression' );
    		$settings 	= array(
 			array( 
 				'name' 		=> 'www/jquery-1.4.4.min.js',
 				'compress'	=> $compress,
 				'auth'		=> false
 			),		
 		);

 		$scripts = array();
 		
 		foreach( $settings as &$script )
		{
 			if( $script['auth'] && !user_is_logged() )
			{
 				continue;
 			};
 			
 			$compression 	= isset($script['compress']) ? $script['compress'] : JavaScriptPacker::COMPRESSION_SKIP;
 			
 			$scripts[]		= array(
 				'file'	=>  JavaScriptPacker::packScript( $script['name'], $compression ),
 				'name'	=> &$script['name']
 			);
 		}
 		
 		$this->smarty->assign(
 			array( 
 				'scripts' 	=> $scripts,
 				'comments'	=> $this->cfg['show_plugin_comments'],
 			) 
 		); 	
    }
}   
?>
