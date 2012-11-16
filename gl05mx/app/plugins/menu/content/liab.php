<?php

	class plg_menu_content_liab extends CBasePlugin 
	{
		public function DoActions() 
		{			
			$this->smarty->assign( array( 'zm' => 'liab' ) );
		}
	}

?>