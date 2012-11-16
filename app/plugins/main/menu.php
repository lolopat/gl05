<?php
class plg_main_menu extends CBasePlugin 
{
	public function DoActions() 
	{
		global $aq;
		
		$subpage = null;
		
		if( !empty( $aq[ 'params' ][ 'subpage' ] ) )
		{
			$subpage = $aq[ 'params' ][ 'subpage' ];
		}
		
		$this->smarty->assign( array( 'menu' 	=> Menu::getMenu(),
									  'subpage'	=> $subpage) );
	}
}
?>