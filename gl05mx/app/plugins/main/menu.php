<?php
class plg_main_menu extends CBasePlugin 
{
	public function DoActions() 
	{
		global $aq;
		
		$this->smarty->assign( array( 'menu' => Menu::getMenu() ) );
	}
}
?>