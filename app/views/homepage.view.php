<?php

	class homepage_view extends CView 
	{
		public function Actions() 
		{
			global $aq;
			
			if( !empty( $aq[ 'params' ][ 'subpage' ] ) )
			{
				$subpage = 'http://' . $_SERVER['HTTP_HOST'] . '/#' . $aq[ 'params' ][ 'subpage' ];
				
				//redir(  $subpage );
			}
		
			$this->AddModule('plg_meta_tags');
			$this->AddModule('plg_main_menu');
			$this->AddModule('plg_language_change');
			$this->SetTemplateName('index');
		}
	}
?>