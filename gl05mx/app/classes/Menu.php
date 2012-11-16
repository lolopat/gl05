<?php

	class Menu
	{
		public static $menu = array(
				'level1' =>
					array
					(
						array(
							'white_black_picture' 	=> 'menuGL05g.png',
							'color_picture'			=> 'menuGL05g100.png',
							'load_content'			=> true,
							'item'					=> 'gl05'
						),
					),
				'level2' =>
					array
					(
						array(
							'white_black_picture' 	=> 'menu_dom_warmiaT.png',
							'color_picture'			=> 'menu_dom_warmia.jpg',
							'load_content'			=> true,
							'item'					=> 'cer06',
						),
					),
				'level3' =>
					array
					(
						array(
							'white_black_picture' 	=> 'menuDNWg.png',
							'color_picture'			=> 'menuDNW.jpg',
							'load_content'			=> true,
							'item'					=> 'dnw',
						),
					),	
				'level4' =>
					array
					(
						array(
							'white_black_picture' 	=> 'menuEUROPAN09g.png',
							'color_picture'			=> 'menuEUROPAN09.jpg',
							'load_content'			=> true,
							'item'					=> 'europan09',
						),
					),
				'level5' =>
					array
					(
						array(
							'white_black_picture' 	=> 'menuKORTYg.png',
							'color_picture'			=> 'menuKORTY.jpg',
							'load_content'			=> true,
							'item'					=> 'korty',
						),
					),
			);
		
		public function DoActions()
		{
		}
		
		public static function getMenu()
		{
			return self::$menu;
		}
	}
	
?>