<?php

	class homepage_view extends CView 
	{
		public function Actions() 
		{
			//$this->AddModule('plg_meta_tags');
			$this->AddModule('plg_main_menu');
			$this->AddModule('plg_language_change');
			$this->SetTemplateName('index');
		}
	}
?>