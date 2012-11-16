<?php

	class plg_language_change extends CBasePlugin 
	{
		public function DoActions() 
		{			
			$this->smarty->assign( array( 'flag_language_pl' => Language::LANGCODE_PL,
										  'flag_language_en' => Language::LANGCODE_EN) );
		}
	}

?>