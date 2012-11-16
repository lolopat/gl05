<?php

	class plg_menu_content_lagi extends CBasePlugin 
	{
		public function DoActions() 
		{			
			$this->smarty->assign( array( 'zm' => 'lagi' ) );
		}
	}

?>